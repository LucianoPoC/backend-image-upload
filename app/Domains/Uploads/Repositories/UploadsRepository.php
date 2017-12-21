<?php

namespace App\Domains\Uploads\Repositories;

use App\Core\Contracts\AbstractRepository;
use App\Domains\Uploads\Models\Uploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UploadsRepository extends AbstractRepository
{
    /**
     * @var Uploads
     */
    protected $model;

    public function __construct(Uploads $uploads)
    {
        $this->model = $uploads;
    }

    /**
     * Get resource on database.
     *
     * @param null $columns
     * @param int|null $id
     * @return mixed
     */
    protected function getResource($columns = null, int $id = null)
    {
        $singleItem = ! empty($id);

        $data = $this->model->all();

        if ($singleItem) {
            Log::info('GET Uploads[{$id}] from database');
            $data = $data->where('id', $id);
        }

        if ($data->isEmpty()) {
            return $this->resourceNotFound();
        }

        $this->data = [
            'status_code' => Response::HTTP_OK,
            'data' => $data
        ];

        return $this->data;
    }

    /**
     * Store new resource.
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        /**
         * @var $file \Illuminate\Http\UploadedFile
         */
        $file = $data['file'];

        $fileName = time() . '.' . $file->getClientOriginalExtension();

        Storage::disk('s3')->put($fileName, file_get_contents($file));

        $this->model = new Uploads();

        $this->model->link = $fileName;

        if (!empty($data['title'])) {
            $this->model->title = $data['title'];
        }

        $this->model->save();

        $data = [
            'status_code'   => Response::HTTP_CREATED,
            'data'          => [$this->model]
        ];

        return $data;
    }

    /**
     * Update resource.
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        $this->model = Uploads::find($id);

        $data = [
            'status_code'   => Response::HTTP_OK,
            'data'          => [$this->model]
        ];

        return $data;
    }

    /**
     * Remove a resource.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $this->model = Uploads::where('id', $id)->first();

        if (empty($this->model)) {
            return $this->resourceNotFound();
        }

        $productsUsingTaxes = $this->model->products()->get()->count();

        if ($productsUsingTaxes) {
            $data = [
                'status_code' => Response::HTTP_LOCKED,
                "message" => 'Can not delete the Tax. Tax with products associated'
            ];

            return $data;
        }

        $result = Uploads::destroy($id);

        $data = [
            'status_code' => Response::HTTP_OK,
            'data' => $result
        ];

        $this->model->deleteItemsFromCache($id);

        return $data;
    }
}
