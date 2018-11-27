<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'url';

    /**
     * Fillable field
     *
     * @var array
     */
    protected $fillable = ['url', 'short_url', 'ip', 'private'];

    protected $hidden = ['deleted_at', 'ip'];

    /**
     * Return the views from the url
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany('App\View');
    }
}
