<?php

namespace App\Demo\Downloader;

use App\Demo\Exception\DownloaderException;

/**
 * Class StreamDownloader
 *
 * @package App\Demo\Downloader
 */
class StreamDownloader
{
    /**
     * Url link
     *
     * @var string
     */
    protected $url;

    /**
     * Destination File name path
     *
     * @var string
     */
    protected $destination;

    /**
     * Stream Read chunk size
     */
    const FILE_CHUNK_SIZE = 1;

    /**
     * Response code for http ok
     */
    const RESPONSE_HTTP_OK = '200';

    /**
     * Processing
     *
     * @var Processor
     */
    protected $processor;

    /**
     * StreamDownloader constructor.
     *
     * @param string $url
     * @param string $destinationPath
     */
    public function __construct(string $url, string $destinationPath)
    {
        $this->sourceUrl = $url;
        $this->destination = $destinationPath;
    }

    /**
     * Will start to download the file
     *
     * @throws DownloaderException
     */
    public function begin()
    {
        if (!$this->ensureUrlIsReadable()) {
            throw new DownloaderException("Source Url is not a valid resource.");
        }

       $this->downloadFromUrl();
    }

    /**
     * Delete destination file
     */
    public function deleteDestinationFile()
    {
        if (file_exists($this->destination)) {
            if ( ! unlink($this->destination)) {
                throw new DownloaderException('File could not able to delete');
            }
        }
    }

    /**
     * Verified that we an reach the source url
     *
     * @return bool
     */
    protected function ensureUrlIsReadable() : bool
    {
        $ch = curl_init($this->sourceUrl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch);

        return $response['http_code'] == self::RESPONSE_HTTP_OK;
    }

    /**
     * Downloads the file from the source url by chunking it
     * in the length specified in constant FILE_CHUNK_SIZE
     *
     * @return int length of bytes read
     *
     * @throws DownloaderException
     */
    protected function downloadFromUrl() : int
    {
        $chunkSize = self::FILE_CHUNK_SIZE * (1024 * 1024);
        $bytesCount = 0;
        $readHandle = fopen($this->sourceUrl, 'rb');
        $writeHandle = fopen($this->destination, 'w');

        if (!$readHandle) {
            throw new DownloaderException("Url cannot be reached.");
        }

        while (!feof($readHandle)) {

            // Read chunk size at a time
            $data = fread($readHandle, $chunkSize);

            // Write to destination
            fwrite($writeHandle, $data, strlen($data));

            // Keep track fo the bytes count received so far from the source url
            $bytesCount += strlen($data);

        }
        $status = fclose($readHandle);
        fclose($writeHandle);

        if (!$status) {
            throw new DownloaderException("Reader terminated unexpectedly.");
        }

        return $bytesCount;
    }
}
