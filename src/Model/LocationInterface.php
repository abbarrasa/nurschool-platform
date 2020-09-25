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

use Nurschool\Entity\Locality;

interface LocationInterface
{
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
    public function getLongitude();

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude);

    /**
     * @return Locality
     */
    public function getLocality();

    /**
     * @param Locality $locality
     */
    public function setLocality(Locality $locality);
}