<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/config.php";

use App\Demo\Command\DownloadFileCommand;
use App\Demo\Downloader\StreamDownloader;
use Symfony\Component\Console\Application;

use App\Demo\DbConnection;
use Illuminate\Database\Capsule\Manager;

$dbConnection = new DbConnection(new Manager, $dbConfig);

$application = new Application();
$application->add(new DownloadFileCommand());
// Register commands
$result = $application->run();
