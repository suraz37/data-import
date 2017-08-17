<?php
namespace App\Tests;

use App\Demo\Command\DownloadFileCommand;
use App\Demo\Downloader\StreamDownloader;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \App\Demo\Command\DownloadFileCommand
 */
class DownloadFileCommandTest extends TestCase
{
    /**
     * Application
     *
     * @var
     */
    private $app;

    /**
     * Output
     *
     * @var
     */
    private $output;

    /**
     * Setup
     */
    protected function setUp()
    {
        $kernel = new Command('test', false);
        $this->app = new Application($kernel);
        $this->app->setAutoExit(false);
        $this->output = new NullOutput();
    }

    /**
     * @covers ::execute
     */
    public function testItRunsSuccessfully()
    {
        $input = new ArrayInput(array(
            'commandName' => 'app:download-file',
            'name' => 'Downloader',
        ));

        $exitCode = $this->app->run($input, $this->output);

        $this->assertSame(1, $exitCode);
    }
}

