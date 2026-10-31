@php
    $input = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm';
    $label = 'block text-sm font-medium text-gray-700';
    $err   = 'mt-1 text-sm text-red-500';
@endphp

<div class="mb-4">
    <label for="title" class="{{ $label }}">Judul Buku</label>
    <input type="text" name="title" id="title" value="{{ old('title', $book?->title) }}" class="{{ $input }}">
    @error('title') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="isbn" class="{{ $label }}">ISBN</label>
    <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book?->isbn) }}"
           placeholder="978-602-03-3295-6" class="{{ $input }}">
    @error('isbn') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="published_year" class="{{ $label }}">Tahun Terbit</label>
    <input type="number" name="published_year" id="published_year"
           value="{{ old('published_year', $book?->published_year) }}" class="{{ $input }}">
    @error('published_year') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="price" class="{{ $label }}">Harga</label>
    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $book?->price) }}" class="{{ $input }}">
    @error('price') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="stock" class="{{ $label }}">Stok</label>
    <input type="number" name="stock" id="stock" value="{{ old('stock', $book?->stock) }}" class="{{ $input }}">
    @error('stock') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-6">
    <label for="author_id" class="{{ $label }}">Penulis</label>
    <select name="author_id" id="author_id" class="{{ $input }}">
        <option value="">-- Pilih Penulis --</option>
        @foreach ($authors as $author)
            <option value="{{ $author->id }}" @selected(old('author_id', $book?->author_id) == $author->id)>
                {{ $author->name }}
            </option>
        @endforeach
    </select>
    @error('author_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>