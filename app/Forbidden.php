<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forbidden extends Model
{
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
