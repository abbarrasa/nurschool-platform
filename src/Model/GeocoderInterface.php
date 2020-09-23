<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Model;

interface GeocoderInterface
{
    //AIzaSyC5LIzIX6aVOlB5rF5VOQ-CM0AA6nVQhRc


    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     */
    public function setAddress(string $address);

    /**
     * @return string
     */
    public function getLatitude();

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude);

    /**
     * @return float
     */
    public function getLongitud();

    /**
     * @param float $longitud
     */
    public function setLongitud(float $longitud);

    /**
     * @return string
     */
    public function getProvince();

    /**
     * @param string $province
     */
    public function setProvince(string $province);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     */
    public function setCity(string $city);
}