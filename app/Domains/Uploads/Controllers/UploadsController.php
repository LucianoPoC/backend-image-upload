<?php

namespace App\Domains\Uploads\Controllers;

use App\Core\Contracts\RepositoryInterface;
use App\Core\Http\Controllers\Controller;
use App\Domains\Uploads\Requests\UploadsRequest;
use App\Domains\Uploads\Responses\UploadsResponse;
use Illuminate\Http\Request;
use LucianoJr\LaravelApiQueryHandler\CollectionHandler;

class UploadsController extends Controller
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->response = new UploadsResponse();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->repository->all();

        if (empty($data['data'])) {
            return $this->responseResolver($data);
        }

        $collectionHandler = new CollectionHandler($data['data'], $request);
        $collectionHandler->handle();

        $paginator = $collectionHandler->paginate();

        $responseData['data'] = $paginator->all();
        $responseData['meta'] = $paginator->getMeta();
        $responseData['status_code'] = $data['status_code'];

        return $this->responseResolver($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param Products $product
     * @internal param int $id
     */
    public function show(int $id, Request $request)
    {
        $data = $this->repository->find($id);

        if (empty($data['data'])) {
            return $this->responseResolver($data);
        }

        $collectionHandler = new CollectionHandler($data['data'], $request);
        $collectionHandler->handle();

        return $this->responseResolver($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UploadsRequest $request)
    {
        $data = $this->repository->store($request->all());

        return $this->responseResolver($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->repository->update($request->all(), $id);

        return $this->responseResolver($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->repository->destroy($id);

        return $this->responseResolver($data);
    }
}
