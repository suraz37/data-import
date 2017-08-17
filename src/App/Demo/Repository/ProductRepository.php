<?php
namespace App\Demo\Repository;

use App\Demo\Entity\Product;

/**
 * Class ProductRepository
 *
 * @package App\Demo\Repository
 */
class ProductRepository
{
    /**
     * Get product
     *
     * @var Product
     */
    protected $product;

    /**
     * ProductRepository constructor.
     *
     * @param Product $product Initialize product model
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Check already exist data and insert into table
     *
     * @param array $data Data from file
     *
     * @return bool
     */
    function insert(array $data) : bool
    {
        return $this->product->insert($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getExistingCodes(array $data): array
    {
        return $this->product
            ->select('product_code as code')
            ->whereIn('product_code', $data)
            ->get()
            ->toArray();
    }
}
