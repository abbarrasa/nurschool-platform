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
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Nurschool\Model\LocationInterface;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

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

            $this->geocodeEntity($metadata, $entity);

            $uow->recomputeSingleEntityChangeSet(
                $em->getClassMetadata(get_class($entity)),
                $entity
            );
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

            $this->geocodeEntity($metadata, $entity);

            $uow->recomputeSingleEntityChangeSet(
                $em->getClassMetadata(get_class($entity)),
                $entity
            );
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @param $entity
     * @throws \Geocoder\Exception\Exception
     */
    private function geocodeEntity(ClassMetadata $metadata, $entity)
    {
        if (!$entity instanceof LocationInterface) {
            throw new \UnexpectedValueException(
                \sprintf('Expected argument of type "%s", "%s" given', LocationInterface::class, \get_class($entity))
            );
        }

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
            $metadata->localityProperty->setValue($entity, $result->getLocality());
            $metadata->firstAdminLevelProperty->setValue($entity, $result->getAdminLevels()->get(1)->getName());

            if ($result->getAdminLevels()->has(2)) {
                $metadata->secondAdminLevelProperty->setValue($entity, $result->getAdminLevels()->get(2)->getName());
            }
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
}