<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimoniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // role & order checks are handled in the controller
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'rating'  => 'required|integer|min:1|max:5',
            'photo'   => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Testimonial content cannot be empty.',
            'content.max'       => 'Testimonial must not exceed 1000 characters.',
            'rating.required'   => 'Rating is required.',
            'rating.min'        => 'Minimum rating is 1 star.',
            'rating.max'        => 'Maximum rating is 5 stars.',
            'photo.required'    => 'Testimonial photo is required.',
            'photo.image'       => 'The file must be an image.',
            'photo.mimes'       => 'Photo format must be jpg, jpeg, png, or webp.',
            'photo.max'         => 'Maximum photo size is 10MB.',
        ];
    }
}