<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forbidden extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forbidden';

    /**
     * Fillable field
     *
     * @var array
     */
    protected $fillable = ['contains'];

    protected $hidden = ['deleted_at'];

}
