<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get a resource list.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * Get a single resource.
     *
     * @param int   $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find(int $id, $columns = ['*']);

    /**
     * Store new resource.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update resource.
     *
     * @param array $data
     * @param int   $id
     *
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * Remove a resource.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function destroy(int $id);

    /**
     * Get the model resource.
     *
     * @return mixed
     */
    public function getModel(): Model;

    /**
     * Set the model resource.
     *
     * @param $model
     *
     * @return mixed
     */
    public function setModel(Model $model);
}
