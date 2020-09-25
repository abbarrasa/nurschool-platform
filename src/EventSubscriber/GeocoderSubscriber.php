<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\EventSubscriber;

use Bazinga\GeocoderBundle\Mapping\ClassMetadata;
use Bazinga\GeocoderBundle\Mapping\Driver\DriverInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Geocoder\Location;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Nurschool\Entity\AdminLevel;
use Nurschool\Entity\Country;
use Nurschool\Entity\Locality;
use Nurschool\Model\LocationInterface;

class GeocoderSubscriber implements EventSubscriber
{
    /** @var DriverInterface */
    private $driver;

    /** @var Provider */
    private $geocoder;

    /**
     * GeocoderSubscriber constructor.
     * @param Provider $geocoder
     * @param DriverInterface $driver
     */
    public function __construct(Provider $geocoder, DriverInterface $driver)
    {
        $this->driver = $driver;
        $this->geocoder = $geocoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$this->driver->isGeocodeable($entity)) {
                continue;
            }

            /** @var ClassMetadata $metadata */
            $metadata = $this->driver->loadMetadataFromObject($entity);

            $this->geocodeEntity($metadata, $em, $entity);

            $this->recomputeEntityChangeSet($uow, $em, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$this->driver->isGeocodeable($entity)) {
                continue;
            }

            /** @var ClassMetadata $metadata */
            $metadata = $this->driver->loadMetadataFromObject($entity);

            if (!$this->shouldGeocode($metadata, $uow, $entity)) {
                continue;
            }

            $this->geocodeEntity($metadata, $em, $entity);

            $this->recomputeEntityChangeSet($uow, $em, $entity);
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @param EntityManagerInterface $entityManager
     * @param $entity
     * @throws \Geocoder\Exception\Exception
     */
    private function geocodeEntity(ClassMetadata $metadata, /*UnitOfWork $unitOfWork, */EntityManagerInterface $entityManager, $entity)
    {
        if (null !== $metadata->addressGetter) {
            $address = $metadata->addressGetter->invoke($entity);
        } else {
            $address = $metadata->addressProperty->getValue($entity);
        }

        if (empty($address)) {
            return;
        }

        $results = $this->geocoder->geocodeQuery(GeocodeQuery::create($address));

        if (!$results->isEmpty()) {
            $result = $results->first();
            $metadata->latitudeProperty->setValue($entity, $result->getCoordinates()->getLatitude());
            $metadata->longitudeProperty->setValue($entity, $result->getCoordinates()->getLongitude());

            if (!$entity instanceof LocationInterface) {
                return;
            }

            $country = $this->geocodeCountry($result, $entityManager);
            $lastAdminLevel = $this->geocodeAdminLevels($result, $country, $entityManager);
            $locality = $this->geocodeLocality($result, $lastAdminLevel, $entityManager);

            $entity->setLocality($locality);
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @param UnitOfWork $unitOfWork
     * @param $entity
     * @return bool
     */
    private function shouldGeocode(ClassMetadata $metadata, UnitOfWork $unitOfWork, $entity): bool
    {
        if (null !== $metadata->addressGetter) {
            return true;
        }

        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        return isset($changeSet[$metadata->addressProperty->getName()]);
    }

    /**
     * @param Location $location
     * @param EntityManagerInterface $entityManager
     * @return Country
     */
    private function geocodeCountry(Location $location, EntityManagerInterface $entityManager): Country
    {
        $name = $location->getCountry()->getName();
        $code = $location->getCountry()->getCode();
        if (null === ($country = $entityManager->getRepository(Country::class)->findOneByName($name))) {
            $country = new Country($name, $code);
            $entityManager->persist($country);
        }

        return $country;
    }

    /**
     * @param Location $location
     * @param Country $country
     * @param EntityManagerInterface $entityManager
     * @return AdminLevel
     */
    private function geocodeAdminLevels(Location $location, Country $country, EntityManagerInterface $entityManager): AdminLevel
    {
        $list = \array_reverse($location->getAdminLevels()->slice(0));
        $parent = null;
        do {
            $item = \array_pop($list);
            $level = $item->getLevel();
            $name = $item->getName();
            if (null === ($lastAdminLevel = $entityManager->getRepository(AdminLevel::class)->findOneByLevelAndName($level, $name, $country))) {
                $code = $item->getCode();
                $lastAdminLevel = new AdminLevel($level, $name, $code);
                $lastAdminLevel->setCountry($country);
                $lastAdminLevel->setParent($parent);
                $entityManager->persist($lastAdminLevel);
            }
            $parent = $lastAdminLevel;
        } while(!empty($list));

        return $lastAdminLevel;
    }

    /**
     * @param Location $location
     * @param AdminLevel $adminLevel
     * @param EntityManagerInterface $entityManager
     * @return Locality
     */
    private function geocodeLocality(Location $location, AdminLevel $adminLevel, EntityManagerInterface $entityManager): Locality
    {
        $name = $location->getLocality();
        if (null === ($locality = $entityManager->getRepository(Locality::class)->findOneByName($name, $adminLevel))) {
            $locality = new Locality($name);
            $locality->setAdminLevel($adminLevel);
            $entityManager->persist($locality);
        }

        return $locality;
    }

    /**
     * @param UnitOfWork $unitOfWork
     * @param EntityManager $entityManager
     * @param object $entity
     */
    private function recomputeEntityChangeSet(UnitOfWork $unitOfWork, EntityManager $entityManager, object $entity): void
    {
        $unitOfWork->recomputeSingleEntityChangeSet(
            $entityManager->getClassMetadata(get_class($entity)),
            $entity
        );

        if ($entity instanceof LocationInterface) {
            $locality = $entity->getLocality();
            $unitOfWork->computeChangeSet(
                $entityManager->getClassMetadata(get_class($locality)),
                $locality
            );

            $adminLevel = $locality->getAdminLevel();
            $parent = $adminLevel->getParent();
            while(null !== $parent) {
                $unitOfWork->computeChangeSet(
                    $entityManager->getClassMetadata(get_class($parent)),
                    $parent
                );

                $parent = $parent->getParent();
            }

            $unitOfWork->computeChangeSet(
                $entityManager->getClassMetadata(get_class($adminLevel)),
                $adminLevel
            );

            $country = $adminLevel->getCountry();
            $unitOfWork->computeChangeSet(
                $entityManager->getClassMetadata(get_class($country)),
                $country
            );
        }
    }
}