<?php

namespace Myrtle\Core\Docks\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Myrtle\Core\Docks\Repository;

class DocksServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAppBindings();
        //$this->registerAdditionalServiceProviders();
    }

    /**
     * Register bindings
     *
     * @return void
     */
    protected function registerAppBindings()
    {
        App::singleton('docks', Repository::class);
    }

    /**
     * Register Additional Service Providers
     *
     * @return void
     */
    protected function registerAdditionalServiceProviders()
    {
        app()->register(DocksManagerServiceProvider::class);
    }
}
