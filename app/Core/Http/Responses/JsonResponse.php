<?php

namespace App\Core\Http\Responses;

use App\Core\Transformers\EmptyResourceKeyDataSerializer;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

abstract class JsonResponse extends BaseJsonResponse
{
    /**
     * @var Manager
     */
    protected $fractal;

    protected $includes;
    /**
     * Handle the response to answer as specific implementation.
     *
     * @param $data
     */
    abstract public function handle($data);

    /**
     * @return mixed
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * @param mixed $includes
     */
    public function setIncludes($includes)
    {
        $this->includes = $includes;
    }

    /**
     * @return mixed
     */
    public function getExcludes()
    {
        return $this->includes;
    }

    protected function resource($data, $transformImplementation)
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new EmptyResourceKeyDataSerializer());

        return new Collection($data, $transformImplementation, 'data');
    }
}
