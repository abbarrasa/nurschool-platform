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


class InvitationCodeGenerator implements InvitationCodeGeneratorInterface
{
    /** @var string */
    private $signingKey;

    public function __construct(string $signingKey)
    {
        $this->signingKey = $signingKey;
    }

    public function createCode(): string
    {
        // generate identifier only once, here a 6 characters length code
        return substr(md5(uniqid(rand(), true)), 0, 6);
    }

    public function createToken(\DateTimeInterface $expiresAt, string $code)
    {
        $encodedData = \json_encode([$code, $expiresAt->getTimestamp()]);

        return $this->getHashedToken($encodedData);
    }

    private function getHashedToken(string $data): string
    {
        return \base64_encode(\hash_hmac('sha256', $data, $this->signingKey));
    }
}