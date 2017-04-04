<?php

namespace Myrtle\Core\Docks;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Repository
{
    private static $protected = [
        AddressesDock::class,
        BugsprayDock::class,
        ConfigurationsDock::class,
        DocksDock::class,
        EmailsDock::class,
        EthnicitiesDock::class,
        GendersDock::class,
        LandingDock::class,
        MaritalStatusesDock::class,
        PermissionsDock::class,
        PhonesDock::class,
        ReligionsDock::class,
        RolesDock::class,
        SettingsDock::class,
        SystemDock::class,
        ThemesDock::class,
        UsersDock::class,
    ];
    /**
     * Return a collection of installed Docks
     *
     */
    public function all()
    {
        return collect($this->dockClassNames())->transform(function ($class, $key) {
            return new $class;
        });
    }

    public function protected()
    {
        return $this->all()->reject(function ($dock) {
            return ! in_array(get_class($dock), static::$protected);
        });
    }

    /**
     * Return a Dock by name
     *
     * @param $name
     *
     * @return Dock
     */
    public function get($name)
    {
        return $this->all()->filter(function ($dock, $key) use ($name) {
            return get_class($dock) === $name || $dock->name === $name;
        })->first();
    }

    /**
     * Return Collection of enabled Docks
     *
     * @return Collection
     */
    public function enabled()
    {
        return $this->all()->reject(function ($dock, $key) {
            return $dock->disabled();
        });
    }

    /**
     * Return a Collection of disabled Docks
     *
     * @return Collection
     */
    public function disabled()
    {
        return $this->all()->diffKeys($this->enabled());
    }

    /**
     * Get a List of Dock class names
     *
     * @return Collection
     */
    protected function dockClassNames()
    {
        return collect(get_declared_classes())->filter(function ($class) {
            return Str::is('ComposerAutoloaderInit*', $class);
        })->flatMap(function ($composerInit) {
            return (new $composerInit)->getLoader()->getClassMap();
        })->reject(function ($path, $class) {
            return !is_subclass_of($class, Dock::class);
        })->transform(function ($value, $key) {
            return $key;
        });
    }
}