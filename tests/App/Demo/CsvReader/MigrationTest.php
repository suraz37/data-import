<?php
namespace App\Tests;

use App\Demo\CsvReader\Migration;
use App\Demo\CsvReader\Reader;
use App\Demo\Repository\OrderRepository;
use App\Demo\Repository\ProductRepository;
use Mockery as m;

use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    /**
     * @var m\MockInterface | \App\Demo\CsvReader\Reader
     */
    protected $reader;

    /** @var  m\MockInterface| \App\Demo\Repository\OrderRepository */
    protected $orderRepo;

    /** @var  m\MockInterface | \App\Demo\Repository\ProductRepository */
    protected $productRepo;

    /**
     * @var Migration
     */
    protected $migration;

    /**
     * Setup the test
     */
    protected function setUp()
    {
        $this->reader = m::mock(Reader::class);
        $this->orderRepo = m::mock(OrderRepository::class);
        $this->productRepo = m::mock(ProductRepository::class);

        $this->migration = new Migration(
            $this->reader,
            $this->orderRepo,
            $this->productRepo
        );

        parent::setUp();
    }

    /**
     * @dataProvider processDataProvider
     */
    public function testProcess($times, $endOfFile, $expected)
    {
        $rows = $this->getFixture();
        $this->reader
            ->shouldReceive('eof')
            ->times($times);

        $this->reader
            ->shouldReceive('getChunkReader')
            ->andReturn($rows);

        $this->reader
            ->shouldReceive('eof')
            ->andReturn($endOfFile);

        $this->productRepo
            ->shouldReceive('getExistingCodes')
            ->with($rows['product']['keys'])
            ->andReturn([]);

        $this->productRepo
            ->shouldReceive('insert')
            ->with($rows['product']['data'])
            ->andReturn(true);

        $this->orderRepo
            ->shouldReceive('getExistingCodes')
            ->with($rows['order']['keys'])
            ->andReturn([]);

        $this->orderRepo
            ->shouldReceive('insert')
            ->with($rows['order']['data'])
            ->andReturn(true);

        $result = $this->migration->process();
        $this->assertEquals($result, $expected);
    }

    /**
     * Get fixture
     *
     * @return array
     */
    public function getFixture()
    {
        return [
            "order" => [
                "data" => [
                    "cks1" => [
                        "order_code" => 'cks1',
                        "client_name" => 'alex-co1',
                        "product_code" => 'table1',
                        "quantity" => 1,
                    ],
                    "cks2" => [
                        "order_code" => 'cks2',
                        "client_name" => 'alex-co2',
                        "product_code" => 'table2',
                        "quantity" => 2,
                    ]
                ],
                "keys" => [
                    "1" => 'cks2',
                    "2" => 'cks3',
                ],
            ],
            "product" => [
                "data" => [
                    "table1" => ["product_code" => 'table1'],
                    "table2" => ["product_code" => 'table2'],
                ],
                "keys" => [
                    "1" => 'table2',
                    "2" => 'table3',
                ],
            ]
        ];
    }

    /**
     * @covers ::getNewCodes
     */
    public function testIfReturnNewCodes()
    {
        $filterCode = [
                        ["code" => 'cks1']
                    ];

        $rows = $this->getFixture();
        $newCodes = $this->getNewCodesFixture();
        $result = $this->migration->getNewCodes($filterCode, $rows['order']['data']);

        $this->assertEquals($result, $newCodes);
    }

    /**
     * Data provider for process
     *
     * @return array
     */
    public function processDataProvider()
    {
        return [
            [1, true, 1000],
            [2, true, 2000],
            [3, true, 3000],
            [4, true, 4000]
        ];
    }

    /**
     * Fixture for verify filter codes.
     *
     * @return array
     */
    public function getNewCodesFixture()
    {
        return [
            "cks2" => [
                "order_code" => 'cks2',
                "client_name" => 'alex-co2',
                "product_code" => 'table2',
                "quantity" => 2,
            ]
        ];
    }
}
