<?php

namespace SlowQueryNotifier;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thomasjohnkane\RenameMe\Skeleton\SkeletonClass
 */
class SlowQueryNotifierFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SlowQueryNotifier::class;
    }
}
