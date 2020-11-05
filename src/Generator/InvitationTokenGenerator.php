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


use Nurschool\Entity\Invitation;
use Nurschool\Model\TokenComponents;

class InvitationTokenGenerator extends AbstractTokenGenerator
{
    public const SELECTOR_LENGTH = 32;
    public const VERIFIER_LENGTH = 20;

    /**
     * @inherits
     */
    public function createToken($data, string $verifier = null): TokenComponents
    {
        if (!$data instanceof Invitation) {
            throw new \Exception();
        }

        if (null === $verifier) {
            $verifier = $this->getRandomAlphaNumStr(self::VERIFIER_LENGTH);
        }

        $encodedData = \json_encode([
            $verifier,
            $data->getCode(),
            ($data->getExpiresAt() !== null ? $data->getExpiresAt()->getTimestamp() : null)
        ]);

        return new TokenComponents(
            $this->getHashedToken($encodedData),
            \md5($data->getCode()),
            $verifier,
            $data->getExpiresAt()
        );
    }
}