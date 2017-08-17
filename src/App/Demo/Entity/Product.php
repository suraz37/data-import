<?php

namespace App\Demo\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @package App\Demo\Entity
 */
class Product extends Model
{
    protected $table = 'product';

    protected $primaryKey = 'id';
}
