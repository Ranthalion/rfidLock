<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateKey extends FormRequest
{

    protected $dontFlash = ['rfid'];

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
        //dd($this->route('member')); //works
        //dd($this->get('member')); // does not work
        return [
            'rfid' => 'required|max:50|unique:members,rfid,'.$this->route('member')
        ];
    }
}
