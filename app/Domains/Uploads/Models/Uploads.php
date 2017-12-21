<?php

namespace App\Domains\Uploads\Models;

use Illuminate\Database\Eloquent\Model;

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
        'views' => 0,
    ];


}
