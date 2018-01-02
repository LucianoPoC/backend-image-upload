<?php

namespace App\Domains\Uploads\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Uploads extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'uploads';

    protected $attributes = [
        'downloads' => 0,
        'views'     => 0,
    ];

    /**
     * Get the link to file.
     *
     * @param string $value
     *
     * @return string
     */
    public function getLinkAttribute($value)
    {
        return Storage::url($value);
    }
}
