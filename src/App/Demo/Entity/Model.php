<?php
namespace App\Demo\Entity;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * Begin Transaction
     */
    public static function beginTransaction()
    {
        self::getConnectionResolver()->connection()->beginTransaction();
    }

    /**
     * Commit
     */
    public static function commit()
    {
        self::getConnectionResolver()->connection()->commit();
    }

    /**
     * RollBack
     */
    public static function rollBack()
    {
        self::getConnectionResolver()->connection()->rollBack();
    }
}
