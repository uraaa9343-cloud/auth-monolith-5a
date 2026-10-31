<?php

namespace App\Http\Controllers;

use App\Exceptions\BookNotAvailableException;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with('author')->latest()->paginate(10);

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);

        return view('books.create', compact('authors'));
    }

    public function store(StoreBookRequest $request): RedirectResponseS
    {
        Book::create($request->validated());

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        $authors = Author::orderBy('name')->get(['id', 'name']);

        return view('books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->validated());

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    // Peminjaman: cek stok dulu, lalu kurangi (transaksi + lock agar aman).
    public function borrow(Book $book): RedirectResponse
    {
        DB::transaction(function () use ($book) {
            $locked = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($locked->stock < 1) {
                throw new BookNotAvailableException($locked->title);
            }

            $locked->decrement('stock');
        });

        return redirect()->route('books.index')->with('success', "Buku '{$book->title}' berhasil dipinjam.");
    }
}