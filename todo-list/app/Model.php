<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Allows some additional utilit functions.
 *
 * @author cwa
 */
class Model extends BaseModel {

    /**
     * Makes it easier to make sure we're using the right table name when
     * doing raw queries and migrations.
     *
     * @return string
     */
    public static function getClassTable() {
        return (new static())->getTable();
    }
}