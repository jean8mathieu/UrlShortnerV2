<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class View extends Model
{
    use SoftDeletes;

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
