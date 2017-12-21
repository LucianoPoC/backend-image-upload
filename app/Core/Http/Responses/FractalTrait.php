<?php

namespace App\Core\Http\Responses;

use League\Fractal\Manager;

trait FractalTrait
{
    protected $fractal;

    public function __construct(Manager $manager)
    {
        $this->fractal = $manager;
    }
}
