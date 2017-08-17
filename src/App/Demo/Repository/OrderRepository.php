<?php
namespace App\Demo\Repository;

use App\Demo\Entity\Order;

/**
 * Class OrderRepository
 *
 * @package App\Demo\Repository
 */
class OrderRepository
{
    /**
     * Order model
     *
     * @var Order
     */
    protected $order;

    /**
     * OrderRepository constructor.
     *
     * @param Order $order initialize order model
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Insert into table
     *
     * @param array $data Data to insert into table
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->order->insert($data);
    }

    /**
     * Get Existing Codes
     *
     * @param array $data
     *
     * @return array
     */
    public function getExistingCodes(array $data): array
    {
        return $this->order
            ->select('order_code as code')
            ->whereIn('order_code', $data)
            ->get()
            ->toArray();
    }
}
