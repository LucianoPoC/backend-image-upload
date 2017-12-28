<?php

namespace App\Domains\Uploads\Responses;

use App\Core\Http\Responses\JsonResponse;
use App\Domains\Uploads\Transformers\UploadsApiTransformer;

class UploadsResponse extends JsonResponse
{
    /**
     * Handle the response to answer as specific implementation.
     *
     * @param $data
     */
    public function handle($data)
    {
        $resource = $this->resource($data['data'], app(UploadsApiTransformer::class));

        if (!empty($data['meta'])) {
            $resource->setMeta($data['meta']);
        }

        $this->setContent($this->fractal->createData($resource)->toJson());
    }
}
