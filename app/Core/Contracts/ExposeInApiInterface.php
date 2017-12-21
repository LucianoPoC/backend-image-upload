<?php

namespace App\Core\Contracts;

interface ExposeInApiInterface
{
    /**
     * List of fields that should be expose in API responses.
     *
     * @return array
     */
    public static function getExposeApiFields();
}
