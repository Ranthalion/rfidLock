<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResource extends FormRequest
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
        return [
            'description' => 'required|max:255|unique:resources,description,'.$this->route('resource'),
            'network_address' => 'nullable|max:255|unique:resources,description,'.$this->route('resource'),
            'api_key' => 'nullable|max:255|unique:resources,description,'.$this->route('resource')
        ];
    }
}
