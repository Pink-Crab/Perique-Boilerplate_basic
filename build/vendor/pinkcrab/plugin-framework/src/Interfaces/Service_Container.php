<?php

declare (strict_types=1);
/**
 * Interface for custom ServiceContainer
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @package PinkCrab\Core
 */
namespace PcLocations_001\PinkCrab\Core\Interfaces;

use PcLocations_001\Psr\Container\ContainerInterface;
interface Service_Container extends \PcLocations_001\Psr\Container\ContainerInterface
{
    /**
     * Binds an object to the constainer
     *
     * @param string $id
     * @param object $service
     * @return void
     */
    public function set($id, $service) : void;
}
