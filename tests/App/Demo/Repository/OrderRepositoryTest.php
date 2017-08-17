<?php
namespace App\Tests;

use App\Demo\Entity\Order;
use App\Demo\Repository\OrderRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use Mockery as m;

/**
 * @coversDefaultClass \App\Demo\Repository\orderRepository
 */
class OrderRepositoryTest extends TestCase
{
    protected $repository;
    protected $repositoryMock;
    protected $dbMock;
    /**
     * Setup
     */
    public function setUp()
    {
        $this->repositoryMock = m::mock(Order::class);
        $this->dbMock         = m::mock(DatabaseManager::class);

        $this->repository = new OrderRepository($this->repositoryMock, $this->dbMock);
    }

    /**
     * Existing codes
     *
     * @covers ::getExistingCodes
     */
    public function testExistingCodes()
    {
        $data = ['o1', 'o2'];

        $this->repositoryMock
            ->shouldReceive('select')
            ->with('order_code as code')
            ->andReturnSelf()
            ->shouldReceive('whereIn')
            ->with('order_code', $data)
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
            'order_code' => '1',
            'order_code' => '2'
        ];

        $this->repositoryMock
            ->shouldReceive('insert')
            ->with($param)
            ->andReturn(true);

        $result = $this->repository->insert($param);

        $this->assertEquals(true, $result);

    }
}
