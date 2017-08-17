<?php

namespace App\Demo\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @package App\Demo\Entity
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'id';
}
