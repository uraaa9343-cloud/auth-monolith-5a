<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Dilempar saat penulis yang masih punya buku hendak dihapus.
class AuthorHasBooksException extends Exception
{
    public function __construct(protected string $authorName, protected int $bookCount)
    {
        parent::__construct(
            "Penulis '{$authorName}' tidak dapat dihapus karena masih memiliki {$bookCount} buku."
        );
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getBookCount(): int
    {
        return $this->bookCount;
    }

    // Catat ke storage/logs/laravel.log
    public function report(): void
    {
        Log::warning($this->getMessage(), [
            'exception'  => static::class,
            'author'     => $this->authorName,
            'book_count' => $this->bookCount,
            'user_id'    => auth()->id(),
        ]);
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error'   => 'author_has_books',
                'details' => ['author' => $this->authorName, 'book_count' => $this->bookCount],
            ], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}