<?php

namespace App\Domains\Uploads\Transformers;

use App\Core\Transformers\BaseTransformer;
use App\Domains\Uploads\Models\Uploads;

class UploadsApiTransformer extends BaseTransformer
{
    /**
     * Mapper of conversion between presenters.
     */
    protected $conversionMapper = [
        'id',
        'title',
        'link',
        'downloads' => [
            'type' => 'integer',
        ],
        'views' => [
            'type' => 'integer',
        ],
        'created_at',
        'updated_at',
    ];

    public function transform(Uploads $uploads)
    {
        foreach ($this->conversionMapper as $from => $to) {
            $this->convert($uploads->toArray(), $from, $to);
        }

        return $this->convertedItem;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function remodel(array $data)
    {
        foreach ($this->conversionMapper as $from => $to) {
            list($to, $from) = $this->flipNames($to, $from);
            $this->convert($data, $from, $to);
        }

        return $this->convertedItem;
    }
}
