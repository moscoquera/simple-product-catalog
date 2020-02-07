<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:2500',
            'category_id'=>'required|exists:categories,id',
            'weight'=>'required|numeric|min:0',
            'usd_price'=>'required|numeric|min:0',
            'images'=>'nullable|array|max:3',
            'images.*'=>'nullable|is_base64_img'
        ];
    }
}
