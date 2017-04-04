<?php

namespace Myrtle\Core\Docks\Facades;

use Myrtle\Docks\Repository;
use Illuminate\Support\Facades\Facade;

/**
 * @see Repository
 */
class Docks extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'docks';
    }
}