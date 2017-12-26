<?php

namespace App\Core\Http\Controllers;

use App\Core\Http\Responses\DefaultResponse;
use App\Core\Http\Responses\ErrorResponse;
use App\Core\Http\Responses\JsonResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var JsonResponse
     */
    protected $response;

    /**
     * Make the response error.
     *
     * @param ErrorResponse $response
     * @param array         $data
     *
     * @return ErrorResponse
     */
    protected function errorResponse(ErrorResponse $response, array $data)
    {
        $this->response = $response;
        $this->response->header('Content-Type', 'application/json');
        $this->response->setStatusCode($data['status_code']);
        $this->response->setContent($data);

        return $this->response;
    }

    protected function responseResolver($data)
    {
        if (!isset($this->response)) {
            $this->response = new DefaultResponse();
        }

        switch ($data['status_code']) {
            case Response::HTTP_CREATED:
            case Response::HTTP_OK:
            case Response::HTTP_PARTIAL_CONTENT:
                $this->response->setStatusCode($data['status_code']);
                $this->response->header('Content-Type', 'application/json');

                if ($this->isDeleteResponse($data['data'])) {
                    $this->response->setContent($data['data']);

                    return $this->response;
                }

                $this->response->handle(
                    $this->normalizeData($data)
                );

                return $this->response;

                break;
            case Response::HTTP_NOT_FOUND:
            case Response::HTTP_NO_CONTENT:
            case Response::HTTP_LOCKED:
            case Response::HTTP_NOT_IMPLEMENTED:
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                return $this->errorResponse(new ErrorResponse(), $data);
                break;
        }
    }

    protected function setResponse(JsonResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Normalize data.
     *
     * @param Model|Collection $data
     *
     * @return Model|Collection
     */
    private function normalizeData($data)
    {
        $dataCollection = new Collection();
        if ($data instanceof Model) {
            $dataCollection->add($data);

            return $dataCollection;
        }

        return $data;
    }

    /**
     * Returns true if current operation is delete.
     *
     * @param $data
     *
     * @return bool
     */
    private function isDeleteResponse($data)
    {
        return is_int($data) && true == $data;
    }

    protected function notImplementedYet()
    {
        return [
            'status_code' => Response::HTTP_NOT_IMPLEMENTED,
            'message'     => Response::$statusTexts[Response::HTTP_NOT_IMPLEMENTED],
        ];
    }
}
