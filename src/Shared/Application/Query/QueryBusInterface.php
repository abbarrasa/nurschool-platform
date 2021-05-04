<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Application\Query;


interface QueryBusInterface
{
    /**
     * @param QueryInterface $query
     * @return Response
     */
    public function ask(QueryInterface $query): Response;
}