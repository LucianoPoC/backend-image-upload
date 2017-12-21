<?php

namespace App\Core\Http\Responses;

use Illuminate\Http\Response;

class ErrorResponse extends Response
{
    /**
     * @var int internal error code
     */
    private $code;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * Set the content on the response.
     *
     * @param  mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        if (isset($this->code)) {
            $content['code'] = $this->code;
        }

        return parent::setContent($content);
    }
}
