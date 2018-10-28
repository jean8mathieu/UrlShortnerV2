<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'view';

    /**
     * Fillable field
     *
     * @var array
     */
    protected $fillable = ['url_id', 'ip'];
}
