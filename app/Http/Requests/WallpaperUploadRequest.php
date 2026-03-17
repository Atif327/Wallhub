<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WallpaperUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'wallpaper' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:102400'], // 100MB
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for your wallpaper.',
            'wallpaper.required' => 'Please select a file to upload.',
            'wallpaper.mimes' => 'Only images (jpg, png, gif, webp) and videos (mp4, webm) are allowed.',
            'wallpaper.max' => 'File size must not exceed 100MB.',
            'categories.*.exists' => 'One or more selected categories are invalid.',
        ];
    }
}
