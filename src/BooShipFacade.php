<?php

namespace BooStudio\BooShip;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BooStudio\BooShip\Skeleton\SkeletonClass
 */
class BooShipFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'booship';
    }
}
