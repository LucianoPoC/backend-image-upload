<?php

namespace App\Core\Http\Presenters;

abstract class Presenter
{
    /**
     * @var mixed
     */
    protected $entity;
    /**
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }
    /**
     * Allow for property-style retrieval
     *
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }
        return $this->entity->{$property};
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

}
