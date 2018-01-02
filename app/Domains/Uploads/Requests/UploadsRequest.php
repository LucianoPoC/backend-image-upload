<?php

namespace App\Domains\Uploads\Requests;

use App\Core\Http\Requests\JsonFormRequest;
use App\Domains\Uploads\Transformers\UploadsApiTransformer;

class UploadsRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $transformer = app(UploadsApiTransformer::class);

        if ($this->all()) {
            $inputs = $transformer->remodel($this->all());

            if ($inputs) {
                $this->replace($inputs);
            }
        }

        return [
            'file'  => 'required|mimes:jpeg,bmp,png|max:10000|dimensions:max_width=1000,max_height=1000',
        ];
    }
}
