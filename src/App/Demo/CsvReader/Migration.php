<?php
namespace App\Demo\CsvReader;

use App\Demo\Repository\OrderRepository;
use App\Demo\Repository\ProductRepository;

class Migration
{
    /**
     * Starting point for reading point.
     */
    const START_READ = 0;

    /**
     * Limit for reading line from file.
     */
    const LIMIT_READ = 1000;

    /**
     * Reader to get line of file
     *
     * @var Reader
     */
    protected $reader;

    /**
     * Insert data to order table
     *
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * Insert data to product table
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Migration constructor.
     *
     * @param Reader            $reader            CSV Reader
     * @param OrderRepository   $orderRepository   Order Repository
     * @param ProductRepository $productRepository Product Repository
     */
    public function __construct(
        Reader $reader,
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->reader            = $reader;
        $this->orderRepository   = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Start process to read from csv and import chunk data to database
     *
     * @return int No of data inserted
     */
    public function process(): int
    {
        $start = self::START_READ;
        $batch = self::LIMIT_READ;

        while ( ! $this->reader->eof()) {
            $dataReader = $this->reader->getChunkReader($start, $batch);

            $this->migrateToDatabase($dataReader);

            $start += $batch;
        }

        return $start;
    }

    /**
     * Migrate chunk data to database
     *
     * @param array $data Data to insert into order and product tables
     */
    public function migrateToDatabase(array $data)
    {
        $existingData = $this->productRepository->getExistingCodes($data['product']['keys']);
        $newData      = $this->getNewCodes($existingData, $data['product']['data']);

        $this->productRepository->insert($newData);

        $existingData = $this->orderRepository->getExistingCodes($data['order']['keys']);
        $newData      = $this->getNewCodes($existingData, $data['order']['data']);

        $this->orderRepository->insert($newData);
    }


    /**
     * Filter already imported codes.
     *
     * @param array $codes
     * @param array $data
     *
     * @return array
     */
    public function getNewCodes(array $codes, array $data): array
    {
        foreach ($codes as $value) {
            if (array_key_exists($value['code'], $data)) {
                unset($data[$value['code']]);
            }
        }

        return $data;
    }
}
