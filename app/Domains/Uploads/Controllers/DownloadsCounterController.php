<?php

namespace App\Domains\Uploads\Controllers;

use App\Core\Contracts\RepositoryInterface;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DownloadsCounterController extends Controller
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository, JsonResponse $response)
    {
        $this->repository = $repository;
        $this->response = $response;
    }

    public function update(int $id)
    {
        $data = $this->repository->find($id);

        if (empty($data['data'])) {
            return $this->responseResolver($data);
        }

        $resource = $data['data']->first();

        $resource->downloads++;

        $resource->save();

        return $this->responseResolver($data);
    }
}
