<?php
namespace App\Tests\Downloader;

use App\Demo\Downloader\StreamDownloader;
use PHPUnit\Framework\TestCase;
use Mockery as m;

/**
 * @coversDefaultClass \App\Demo\Downloader\StreamDownloader
 */
class StreamDownloaderTest extends TestCase
{
    /**
     *  Downloader
     *
     * @var
     */
    protected $downloader;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->downloader = new StreamDownloader('source-url', 'downloaded-file');
    }

    /**
     * @covers ::begin
     *
     * @expectedException \App\Demo\Exception\DownloaderException
     * @expectedExceptionMessage Source Url is not a valid resource.
     */
    public function testBeginThrowsDownloaderException()
    {
        $mock = m::mock(StreamDownloader::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mock->shouldReceive('ensureUrlIsReadable')
            ->once()
            ->andReturn(false);

        $mock->begin();
    }

    /**
     * @covers ::begin
     */
    public function testBegin()
    {
        $mock = m::mock(StreamDownloader::class)->makePartial()
                 ->shouldAllowMockingProtectedMethods();

        $mock->shouldReceive('ensureUrlIsReadable')
             ->once()
             ->andReturn(true);

        $mock->shouldReceive('downloadFromUrl')
             ->once()
             ->andReturn(12);

        $this->assertNull($mock->begin());
    }
}
