<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bans extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bans';

    /**
     * Fillable field
     *
     * @var array
     */
    protected $fillable = ['ip', 'notes'];

    protected $hidden = ['deleted_at'];

}
