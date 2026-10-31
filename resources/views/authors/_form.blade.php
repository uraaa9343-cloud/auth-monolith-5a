@php
    $input = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm';
    $label = 'block text-sm font-medium text-gray-700';
    $err   = 'mt-1 text-sm text-red-500';
@endphp

<div class="mb-4">
    <label for="name" class="{{ $label }}">Nama Penulis</label>
    <input type="text" name="name" id="name" value="{{ old('name', $author?->name) }}" class="{{ $input }}">
    @error('name') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="email" class="{{ $label }}">Email</label>
    <input type="text" name="email" id="email" value="{{ old('email', $author?->email) }}" class="{{ $input }}">
    @error('email') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>

<div class="mb-6">
    <label for="bio" class="{{ $label }}">Bio</label>
    <textarea name="bio" id="bio" rows="4" class="{{ $input }}">{{ old('bio', $author?->bio) }}</textarea>
    @error('bio') <p class="{{ $err }}">{{ $message }}</p> @enderror
</div>