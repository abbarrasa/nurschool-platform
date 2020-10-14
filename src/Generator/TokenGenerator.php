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

class TokenGenerator implements TokenGeneratorInterface
{
    /** @var string */
    protected $signingKey;

    public function __construct(string $signingKey)
    {
        $this->signingKey = $signingKey;
    }

    public function createToken(string $data, \DateTimeInterface $expiresAt = null, string $verifier = null): TokenComponents
    {
        if (null === $verifier) {
            $verifier = $this->getRandomAlphaNumStr();
        }

        $selector = $this->getRandomAlphaNumStr();

        $encodedData = \json_encode([$verifier, $data, $expiresAt->getTimestamp()]);

        return new TokenComponents(
            $this->getHashedToken($encodedData),
            $selector,
            $expiresAt
        );
    }

    protected function getHashedToken(string $data): string
    {
        return \base64_encode(\hash_hmac('sha256', $data, $this->signingKey));
    }

    protected function getRandomAlphaNumStr(): string
    {
        $string = '';
        while(($len = \strlen($string)) < 20) {
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