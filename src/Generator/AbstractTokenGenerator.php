<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Generator;


use Nurschool\Model\TokenComponents;

abstract class AbstractTokenGenerator
{
    /** @var string */
    protected $signingKey;

    public function __construct(string $signingKey)
    {
        $this->signingKey = $signingKey;
    }

    /**
     * Creates a token for a data.
     *
     * @param $data
     * @param string|null $verifier
     * @return TokenComponents
     */
    abstract public function createToken($data, string $verifier = null): TokenComponents;

    /**
     * Gets hashed data to create a token. Hashed data is signed.
     *
     * @param string $data
     * @return string
     */
    protected function getHashedToken(string $data): string
    {
        return \base64_encode(\hash_hmac('sha256', $data, $this->signingKey));
    }

    /**
     * Get a random encoded string.
     *
     * @param int $length
     * @return string
     * @throws \Exception
     */
    protected function getRandomAlphaNumStr(int $length): string
    {
        $string = '';
        while(($len = \strlen($string)) < $length) {
            $size = 20 - $len;

            $bytes = \random_bytes($size);

            $string .= \substr(
                \str_replace(['/', '+', '='], '', \base64_encode($bytes)),
                0,
                $size
            );
        }

        return $string;
    }
}