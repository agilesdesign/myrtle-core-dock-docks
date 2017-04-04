<?php

namespace Myrtle\Core\Docks;

use Myrtle\Core\Docks\Policies\DocksDockPolicy;

class DocksDock extends Dock
{
    /**
     * Description for Dock
     *
     * @var string
     */
    public $description = 'Docks management';

    /**
     * Explicit Gate definitions
     *
     * @var array
     */
    public $gateDefinitions = [
        self::class . '.admin' => DocksDockPolicy::class . '@admin',
        self::class . '.access-admin' => DocksDockPolicy::class . '@accessAdmin',
        self::class . '.edit-settings' => DocksDockPolicy::class . '@editSettings',
        self::class . '.view-settings' => DocksDockPolicy::class . '@viewSettings',
        self::class . '.edit-permissions' => DocksDockPolicy::class . '@editPermissions',
        self::class . '.view-permissions' => DocksDockPolicy::class . '@viewPermissions',
    ];

    /**
     * Policy mappings
     *
     * @var array
     */
    public $policies = [
        DocksDockPolicy::class => DocksDockPolicy::class,
    ];

    /**
     * List of config file paths to be loaded
     *
     * @return array
     */
    public function configPaths()
    {
        return [
            'docks.' . self::class => dirname(__DIR__, 2) . '/config/docks/docks.php',
            'abilities' => dirname(__DIR__, 2) . '/config/abilities.php',
        ];
    }

    /**
     * List of routes file paths to be loaded
     *
     * @return array
     */
    public function routes()
    {
        return [
            'admin' => dirname(__DIR__, 2) . '/routes/admin.php',
        ];
    }
}
