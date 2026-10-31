<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Bersihkan ISBN (hapus "-" dan spasi) sebelum divalidasi.
    protected function prepareForValidation(): void
    {
        if ($this->filled('isbn')) {
            $this->merge([
                'isbn' => strtoupper(str_replace(['-', ' '], '', (string) $this->input('isbn'))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'string', 'regex:/^(\d{9}[\dX]|\d{13})$/', $this->isbnUniqueRule()],
            'published_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price'          => ['required', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'author_id'      => ['required', 'integer', 'exists:authors,id'],
        ];
    }

    // Dioverride di UpdateBookRequest agar ISBN milik buku sendiri diabaikan.
    protected function isbnUniqueRule(): Unique
    {
        return Rule::unique('books', 'isbn');
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'title.max'      => 'Judul buku maksimal 255 karakter.',

            'isbn.required'  => 'ISBN wajib diisi.',
            'isbn.regex'     => 'Format ISBN tidak valid. Gunakan ISBN-10 atau ISBN-13.',
            'isbn.unique'    => 'ISBN sudah terdaftar pada buku lain.',

            'published_year.required' => 'Tahun terbit wajib diisi.',
            'published_year.integer'  => 'Tahun terbit harus berupa bilangan bulat.',
            'published_year.min'      => 'Tahun terbit minimal 1900.',
            'published_year.max'      => 'Tahun terbit maksimal ' . date('Y') . '.',

            'price.required' => 'Harga buku wajib diisi.',
            'price.numeric'  => 'Harga harus berupa angka.',
            'price.min'      => 'Harga tidak boleh negatif.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer'  => 'Stok harus berupa bilangan bulat.',
            'stock.min'      => 'Stok tidak boleh negatif.',

            'author_id.required' => 'Penulis wajib dipilih.',
            'author_id.exists'   => 'Penulis yang dipilih tidak valid.',
        ];
    }
}