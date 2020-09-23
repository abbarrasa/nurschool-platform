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
    protected $longitud;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $province;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

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
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param float $longitud
     * @return $this
     */
    public function setLongitud(float $longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     * @return $this
     */
    public function setProvince(string $province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }
}