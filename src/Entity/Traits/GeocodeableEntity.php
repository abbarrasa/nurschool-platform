<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Entity\Traits;


use Bazinga\GeocoderBundle\Mapping\Annotations as Geocoder;
use Doctrine\ORM\Mapping as ORM;

trait GeocodeableEntity
{
    /**
     * @Geocoder\Address()
     * @ORM\Column(type="string", length=512)
     */
    protected $address;

    /**
     * @Geocoder\Latitude()
     * @ORM\Column(type="float")
     */
    protected $latitude;

    /**
     * @Geocoder\Longitude()
     * @ORM\Column(type="float")
     */
    protected $longitude;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }
}