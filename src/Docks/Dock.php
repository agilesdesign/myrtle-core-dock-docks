<?php

namespace Myrtle\Core\Docks;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Myrtle\Core\Docks\Facades\Docks;
use Myrtle\Permissions\Models\Traits\DefinesAbilities;

abstract class Dock
{
    use DefinesAbilities;

    const PERMISSIONABLE_TYPES = ['roles', 'users'];

    /**
     * Console Commands
     *
     * @var array
     */
    public $commands = [];

    /**
     * Description for Dock
     *
     * @var string
     */
    public $description;

    /**
     * Exceptum mappings
     *
     * @var array
     */
    public $exceptumMap = [];

    /**
     * Explicit Gate definitions
     *
     * @var array
     */
    public $gateDefinitions = [];

    /**
     * Eloquent morph mappings
     *
     * @var array
     */
    public $morphMap = [];

    /**
     * Policy mappings
     *
     * @var array
     */
    public $policies = [];

    /**
     * List of providers to be registered
     *
     * @var array
     */
    public $providers = [];

    /**
     * List of config file paths to be loaded
     *
     * @return array
     */
    protected function configPaths()
    {
        return [];
    }

    /**
     * List of migration file paths to be loaded
     *
     * @return array
     */
    protected function migrationPaths()
    {
        return [];
    }

    /**
     * List of routes file paths to be loaded
     *
     * @return array
     */
    protected function routes()
    {
        return [];
    }

    /**
     * Build dot separation config accessor for dock
     *
     * @return string
     */
    final public function configBase()
    {
        return 'docks.' . static::class;
    }

    /**
     * Parse class name into friendly name for dock
     *
     * @return string
     */
    protected function name()
    {
        return Str::lower(Str::replaceLast('Dock', '', class_basename($this)));
    }

    /**
     * Administrator Routes to be registered
     *
     * @return \Closure
     */
    protected function adminRoutes()
    {
        return false;
    }

    /**
     * Front Routes to be registered
     *
     * @return \Closure
     */
    protected function frontRoutes()
    {
        return false;
    }

    /**
     * Boot View Composers
     */
    protected function viewComposers()
    {
        return;
    }

    /**
     * Determine if dock is enabled
     *
     * @return bool
     */
    public function protected()
    {
        return (bool) Docks::protected()->contains(function($dock) {
            return get_class($dock) === static::class;
        });
    }

    /**
     * Determine if dock is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        return (bool) config($this->configBase() . '.enabled', false) || $this->protected();
    }

    /**
     * Enable Dock
     *
     * @return $this
     */
    public function enable()
    {
        config()->set($this->configBase() . '.enabled', true);

        config()->save();

        sleep(2);

        return $this;
    }

    /**
     * Determine if dock is disabled
     *
     * @return bool
     */
    public function disabled()
    {
        return ! $this->enabled();
    }

    /**
     * Disable Dock
     *
     * @return $this
     */
    public function disable()
    {
        config()->set($this->configBase() . '.enabled', false);

        config()->save();

        sleep(2);

        return $this;
    }
    /**
     * Get storage path for dock settings
     *
     * @return string
     */
    protected function settingsStoragePath()
    {
        return 'config/docks/' . $this->name . '.json';
    }

    /**
     * Get settings for the dock
     *
     * @return string
     */
    public function settings()
    {
        return Config::get($this->configBase(), []);
    }

    /**
     * Determine if dock has settings
     *
     * @return bool
     */
    public function hasSettings()
    {
        return (bool)$this->settings();
    }

    /**
     * Get a setting for dock
     *
     * @param $key
     * @param null $default
     *
     * @return string
     */
    public function getSetting($key, $default = null)
    {
        return Config::get($this->configBase() . '.' . $key, $default);
    }

    /**
     * Set a setting for dock
     *
     * @param $key
     * @param $value
     *
     * @return string
     *
     */
    public function setSetting($key, $value)
    {
        Config::set($this->configBase() . '.' . $key, $value);

        return $this;
    }

    /**
     * Store dock settings
     *
     * @param $key
     *
     * @return $this
     */
    public function storeSettings()
    {
        if (!File::exists(storage_path('config/docks'))) {
            File::makeDirectory(storage_path('config/docks'));
        }

        $data = json_encode($this->settings());

        File::put(storage_path($this->settingsStoragePath()), $data);

        //Storage::put($this->settingsStoragePath(), '<?php return ' . var_export(['settings' => $this->settings], true) . ';' . PHP_EOL);

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }
        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key);
        }
    }

    /**
     * Determine if getMutator exists
     *
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    /**
     * Resolve requested property through getMutator
     *
     */
    protected function mutateAttribute($key)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}();
    }
}