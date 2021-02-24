<?php


namespace Nurschool\Controller;


abstract class AbstractApiController
{
    /**
     * Returns a DTO type.
     *
     * Used to create a DTO object from the request content.
     *
     * @return string
     */
    abstract public function getDtoClassName(): string;
}