<?php

namespace App\Core\Http\Presenters;

abstract class ApiPresenter extends Presenter
{
    /**
     * Should return the fields to be presentable in the api response.
     *
     * @return mixed
     */
    abstract public function api();
}
