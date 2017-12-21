<?php

namespace App\Core\Http\Responses;

class DefaultResponse extends JsonResponse
{
    /**
     * Handle the response to answer as specific implementation.
     * @param $data
     */
    public function handle($data)
    {
        $this->setContent($data);
    }
}
