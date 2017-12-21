<?php

namespace App\Core\Contracts;

use App\Core\Http\Responses\ErrorCodeEnumeration;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;
    protected $data;

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->getResource($columns);
    }

    /**
     * Get resource by id.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, $columns = ['*'])
    {
        return $this->getResource($columns, $id);
    }

    /**
     * When resource does not exist on database.
     *
     * @return array
     */
    protected function resourceNotFound(): array
    {
        $data = [
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => "Resources not found",
            'code' => ErrorCodeEnumeration::RESOURCE_NOT_FOUND
        ];

        return $data;
    }

    /**
     * Get resource on database.
     *
     * @param null $columns
     * @param int|null $id
     * @return mixed
     */
    abstract protected function getResource($columns = null, int $id = null);

    /**
     * Get the model resource.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     *
     * @return mixed|void
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }
}
