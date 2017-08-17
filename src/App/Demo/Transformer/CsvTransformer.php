<?php
namespace App\Demo\Transformer;

use LimitIterator;

/**
 * Class CsvTransformer
 * The CSV Transformer breaks down and parses each row from the CSV file. It transforms the CSV data and create store
 * in a data-structure for easy parsing using the following Algorithm:
 *
 * Input Data:
 *  Order Code, Client Name, product code, Quantity
 *  ord_1, client_1, SM001, 1
 *  ord_2, client_2, SM002, 2
 *
 * Algorithm:
 *  - Separate attributes for product and order
 *  - For product only product code
 *  - For order , it will need order code, client name and quantity
 *  - Separate data to map with product and order table
 *  - Separate unique code for product and order
 *
 * Output:
 *  sample \App\Demo\MigrationTest getFixture()
 *  [order][data] => []
 * [order][keys] => []
 * [product][data] => []
 * [product][keys] => []
 *
 *
 * Algorithm:
 * @package App\Demo\Transformer
 */
class CsvTransformer
{
    const ORDER_CODE = 0;
    const CLIENT_NAME = 1;
    const PRODUCT_CODE = 2;
    const QUANTITY = 3;
    /**
     * Data mapper
     *
     * @param LimitIterator $chunkReader Data from limit iterator
     *
     * @return array
     */
    public function transform(LimitIterator $chunkReader): array
    {
        $result = [];

        foreach ($chunkReader as $key => $line) {
            // Skip the blank rows, we don't really care about them.
            if (empty($line[0])) {
                continue;
            }

            $row = [];
            $row['order_code'] = $line[self::ORDER_CODE] ?? '';
            $row['client_name'] = $line[self::CLIENT_NAME] ?? '';
            $row['product_code'] = $line[self::PRODUCT_CODE] ?? '';
            $row['quantity'] = $line[self::QUANTITY] ?? '';

            $order_code = $row['order_code'];
            $product_code = $row['product_code'];

            $result['order']['data'][$order_code] = $row;
            $result['order']['keys'][] = $order_code;

            $result['product']['data'][$product_code]['product_code']
                = $row['product_code'];
            $result['product']['keys'][] = $row['product_code'];
        }

        return $result;
    }
}
