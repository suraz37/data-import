<?php
namespace App\Demo\CsvReader;

use App\Demo\Transformer\CsvTransformer;
use LimitIterator;
use SplFileObject;

/**
 * Read a line in file
 */
class Reader
{
    /**
     * Reader
     *
     * @var SplFileObject
     */
    protected $reader;

    /**
     * Transformer to map data and key.
     *
     * @var TransformerInterface
     */
    protected $transformer;

    /**
     * Reader constructor.
     *
     * @param string         $filePath Read file
     * @param CsvTransformer $transformer Transformer
     */
    public function __construct(string $filePath, CsvTransformer $transformer)
    {
        $this->reader = new SplFileObject($filePath);
        $this->reader->setFlags(SplFileObject::READ_CSV);
        $this->transformer = $transformer;
    }

    /**
     * Get chunk link of data
     *
     * @param int $start starting point
     * @param int $limit limit line to read
     *
     * @return array
     *
     * YESKO NI SAKINCHA JASTO CHA. LimitIterator bata j aaucha tehi rakhne expectation ma
     */
    public function getChunkReader(int $start, int $limit) : array
    {
        return $this->transformer->transform(new LimitIterator($this->reader, $start, $limit));
    }

    /**
     * End of file
     *
     * @return bool
     *
     * YESKO SAKINCHA
     */
    public function eof()
    {
        return $this->reader->eof();
    }
}
