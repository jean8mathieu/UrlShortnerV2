<?php

namespace App\Models;

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

    protected $casts = ['clicks' => 'integer'];

    /**
     * Return the views from the url
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany(View::class);
    }

    /**
     * The domain to block for a url: lowercase and without a leading "www."
     *
     * @param string $url
     * @return string|null
     */
    public static function hostOf($url)
    {
        $host = parse_url((string) $url, PHP_URL_HOST);

        return $host ? preg_replace('/^www\./', '', strtolower($host)) : null;
    }
}
