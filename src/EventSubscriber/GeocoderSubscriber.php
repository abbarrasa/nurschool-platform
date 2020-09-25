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

            $this->geocodeEntity($metadata, $uow, $em, $entity);

            $this->recomputeSingleEntityChangeSet($uow, $em, $entity);
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

            $this->geocodeEntity($metadata, $uow, $em, $entity);

            $this->recomputeSingleEntityChangeSet($uow, $em, $entity);
        }
    }

    /**
     * @param ClassMetadata $metadata
//     * @param UnitOfWork $unitOfWork
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

            $countryName = $result->getCountry()->getName();
            $countryCode = $result->getCountry()->getCode();
            if (null === ($country = $entityManager->getRepository(Country::class)->findByName($countryName))) {
                $country = new Country($countryName, $countryCode);
                $entityManager->persist($country);
//                $unitOfWork->recomputeSingleEntityChangeSet(
//                    $entityManager->getClassMetadata(get_class($country)),
//                    $country
//                );
            }

            $adminLevels = $result->getAdminLevels();
            $parent = null;
            do {
                $adminLevel = $adminLevels->first();
                $level = $adminLevel->getLevel();
                $name = $adminLevel->getName();
                if (null === ($adminLevelEntity = $entityManager->getRepository(AdminLevel::class)->findOneByLevelAndName($level, $name, $country))) {
                    $code = $adminLevel->getCode();
                    $adminLevelEntity = new AdminLevel($level, $name, $code);
                    $adminLevelEntity->setCountry($country);
                    $adminLevelEntity->setParent($parent);
                    $entityManager->persist($adminLevelEntity);
                }
                $parent = $adminLevel;
                $adminLevels = $adminLevels->slice(1);
            } while(!empty($adminLevels));

            $localityName = $result->getLocality();
            if (null === ($locality = $entityManager->getRepository(Locality::class)->findOneByName($localityName, $adminLevel))) {
                $locality = new Locality($localityName);
                $locality->setAdminLevel($adminLevel);
                $entityManager->persist($locality);
            }

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

    private function recomputeSingleEntityChangeSet(UnitOfWork $unitOfWork, EntityManager $entityManager, object $entity): void
    {
        $unitOfWork->recomputeSingleEntityChangeSet(
            $entityManager->getClassMetadata(get_class($entity)),
            $entity
        );
    }
}