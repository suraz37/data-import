<?php

namespace App\Demo;

use Illuminate\Database\Capsule\Manager;

/**
 * Main Application
 */
class DbConnection
{
    /**
     * Database initialize
     *
     * @var Manager
     */
    protected $capsule;

    /**
     * Database settings
     *
     * @var array
     */
    protected $dbSettings;

    /**
     * Application constructor.
     *
     * @param Manager $capsule    Initialize database
     * @param array   $dbSettings Database settings
     */
    public function __construct(Manager $capsule, array $dbSettings)
    {
        $this->capsule = $capsule;
        $this->dbSettings = $dbSettings;

        $this->bootstrap();
    }

    /**
     * Bootstrap the connection
     */
    protected function bootstrap()
    {
        $this->capsule->addConnection($this->dbSettings);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
}
