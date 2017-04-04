<?php

namespace Myrtle\Core\Docks\Providers;

use Myrtle\Docks\Dock;
use Illuminate\Support\Str;
use Myrtle\Roles\Models\Role;
use Myrtle\Users\Models\User;
use Exceptum\Facades\Exceptum;
use Illuminate\Console\Command;
use Myrtle\Docks\Facades\Docks;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class DocksManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootRouteBindings();
        $this->bootViewComposers();
        $this->bootInstalledDocks();
        $this->bootEnabledDocks();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerInstalledDocks();
        $this->registerEnabledDocks();
    }

    /**
     * Bootstrap Routes of each Dock with an instance of that dock
     *
     * @return void
     */
    protected function bootRouteBindings()
    {
        Route::bind('dock', function ($value) {
            return Docks::get($value);
        });
    }

    /**
     * Bootstrap View Composers
     *
     * @return void
     */
    protected function bootViewComposers()
    {
        Docks::enabled()->each(function ($dock, $key) {
            View::composer('admin::docks.' . $dock->name . '.*', function ($view) use ($dock) {
                $view->withDock($dock);
            });

            View::composer('admin::' . $dock->name . '.*', function ($view) use ($dock) {
                // due to the wildcard and the fact that all dock permissions are managed
                // in views that look like 'admin::docks.users.permissions.edit'
                // the docks dock would be passed to all other docks views like this
                // this ensures that the appropriate dock
                // is passed to the view
                if (!Str::contains($view->name(), 'admin::docks') || Str::is($view->name(), 'admin::docks.index')) {
                    $view->withDock($dock);
                }
            });

            View::composer('admin::docks.' . $dock->name . '.*', function ($view) use ($dock) {
                $users = User::all()->keyBy('id')->map(function ($user, $key) {
                    return '(#' . $user->id . ')' . ' ' . $user->name->lastFirst;
                })->toArray();

                $view->withRoles(Role::permissionable()->pluck('name', 'id'));

                $view->withUsers($users);
            });
        });
    }

    /**
     * Bootstrap functionality for all docks
     *
     * @return void
     */
    protected function bootInstalledDocks()
    {
        Docks::all()->each(function ($dock, $key) {
            collect($dock->migrationPaths)->each(function ($path, $key) {
                $this->loadMigrationsFrom($path);
            });
        });
    }

    /**
     * Bootstrap functionality specific to enabled docks
     *
     * @return void
     */
    protected function bootEnabledDocks()
    {
        Docks::enabled()->each(function ($dock) {
            collect($dock->policies)->each(function ($policy, $key) {
                Gate::policy($key, $policy);
            });

            collect($dock->gateDefinitions)->each(function ($policy, $ability) {
                Gate::define($ability, $policy);
            });

            if ($oldFront = $dock->frontRoutes) {
                Route::group(['middleware' => 'web'], $oldFront);
            }

            if ($oldAdmin = $dock->adminRoutes) {
                Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'can:system.access-admin', 'can:' . get_class($dock) . '.access-admin']], $oldAdmin);
            }

            $this->bootDockRoutes($dock);

            $dock->viewComposers;
        });
    }

    protected function bootDockRoutes(Dock $dock)
    {
        collect($dock->routes)->filter(function ($path, $key) {
            return method_exists($this, 'boot' . ucfirst($key) . 'Routes');
        })->each(function ($path, $key) use ($dock) {
            $method = 'boot' . ucfirst($key) . 'Routes';
            $this->$method($dock);
        });
    }

    protected function bootAdminRoutes(Dock $dock)
    {
        Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'can:system.access-admin', 'can:' . get_class($dock) . '.access-admin']], function() use ($dock) {
            require $dock->routes['admin'];
        });
    }

    protected function bootFrontRoutes(Dock $dock)
    {
        Route::group(['middleware' => 'web'], function() use ($dock) {
            require $dock->routes['front'];
        });
    }

    /**
     * Register the application services for all docks.
     *
     * @return void
     */
    protected function registerInstalledDocks()
    {
        Docks::all()->each(function ($dock) {
            collect($dock->configPaths)->each(function ($path, $key) {
                $this->mergeConfigFrom($path, $key);
            });
        });
    }

    /**
     * Register the application services for enablec docks.
     *
     * @return void
     */
    protected function registerEnabledDocks()
    {
        Docks::enabled()->each(function ($dock) {
            collect($dock->providers)->each(function ($provider) {
                App::register($provider);
            });

            if ($dock->morphMap) {
                Relation::morphMap($dock->morphMap);
            }

            collect($dock->exceptumMap)->each(function ($abettor, $exception) {
                Exceptum::map($exception, $abettor);
            });

            collect($dock->commands)->filter(function($class, $key) {
                return is_a($class, Command::class, true);
            })->each(function($class) {
                $this->commands($class);
            });
        });
    }
}
