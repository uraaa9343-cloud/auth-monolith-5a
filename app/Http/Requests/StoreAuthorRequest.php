<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $this->emailUniqueRule()],
            'bio'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    // Dioverride di UpdateAuthorRequest agar email milik penulis sendiri diabaikan.
    protected function emailUniqueRule(): Unique
    {
        return Rule::unique('authors', 'email');
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama penulis wajib diisi.',
            'name.max'       => 'Nama penulis maksimal 255 karakter.',

            'email.required' => 'Email penulis wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.max'      => 'Email maksimal 255 karakter.',
            'email.unique'   => 'Email sudah digunakan oleh penulis lain.',

            'bio.max'        => 'Bio maksimal 1000 karakter.',
        ];
    }
}