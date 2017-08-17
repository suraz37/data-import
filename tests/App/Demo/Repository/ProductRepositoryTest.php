<?php
namespace App\Tests\Repository;

use App\Demo\Entity\Product;
use App\Demo\Repository\ProductRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use Mockery as m;

/**
 * @coversDefaultClass \App\Demo\Repository\ProductRepository
 */
class ProductRepositoryTest extends TestCase
{
    protected  $repositoryMock;
    protected $repository;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->repositoryMock = m::mock(Product::class);
        $this->dbMock         = m::mock(DatabaseManager::class);

        $this->repository = new productRepository($this->repositoryMock, $this->dbMock);
    }

    /**
     * Existing codes
     *
     * @covers ::getExistingCodes
     */
    public function testExistingCodes()
    {
        $data = ['p1', 'p2'];

        $this->repositoryMock
            ->shouldReceive('select')
            ->with('product_code as code')
            ->andReturnSelf()
            ->shouldReceive('whereIn')
            ->with('product_code', $data)
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->andReturn($data);

        $result = $this->repository->getExistingCodes($data);

        $this->assertEquals($data, $result);
    }

    /**
     * @covers ::insert
     */
    public function testInsert()
    {
        $param = [
            'product_code' => '1',
            'product_code' => '2'
        ];

        $this->repositoryMock
            ->shouldReceive('insert')
            ->with($param)
            ->andReturn(true);

        $result = $this->repository->insert($param);

        $this->assertEquals(true, $result);
    }
}
