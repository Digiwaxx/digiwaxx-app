<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Track Request Validation
 *
 * Validates track creation data
 */
class StoreTrackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by TrackPolicy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'artist' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\&]+$/', // Allow alphanumeric and common punctuation
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\&\(\)]+$/',
            ],
            'time' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[0-9\:]+$/', // Format: MM:SS or HH:MM:SS
            ],
            'status' => [
                'nullable',
                'integer',
                'in:0,1', // 0 = inactive, 1 = active
            ],
            'pageImage' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120', // 5MB in kilobytes
            ],
            'client' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'genre' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'subGenre' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'bpm' => [
                'nullable',
                'integer',
                'min:1',
                'max:300',
            ],
            'album' => [
                'nullable',
                'string',
                'max:255',
            ],
            'label' => [
                'nullable',
                'string',
                'max:255',
            ],
            'producer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'writer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'featured_artist_1' => [
                'nullable',
                'string',
                'max:255',
            ],
            'featured_artist_2' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'artist.required' => 'Artist name is required.',
            'artist.regex' => 'Artist name contains invalid characters.',
            'title.required' => 'Track title is required.',
            'title.regex' => 'Track title contains invalid characters.',
            'time.regex' => 'Invalid time format. Use MM:SS or HH:MM:SS.',
            'pageImage.image' => 'File must be an image.',
            'pageImage.mimes' => 'Image must be: jpeg, jpg, png, gif, or webp.',
            'pageImage.max' => 'Image size must not exceed 5MB.',
            'bpm.max' => 'BPM must be between 1 and 300.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize string inputs
        $this->merge([
            'artist' => trim($this->artist ?? ''),
            'title' => trim($this->title ?? ''),
            'time' => trim($this->time ?? ''),
            'album' => trim($this->album ?? ''),
            'label' => trim($this->label ?? ''),
            'producer' => trim($this->producer ?? ''),
            'writer' => trim($this->writer ?? ''),
        ]);
    }
}
