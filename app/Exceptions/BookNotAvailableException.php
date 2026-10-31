<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Dilempar saat buku dengan stok 0 dipinjam.
class BookNotAvailableException extends Exception
{
    public function __construct(protected string $bookTitle)
    {
        parent::__construct("Buku '{$bookTitle}' tidak tersedia karena stok habis.");
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    // Catat ke storage/logs/laravel.log
    public function report(): void
    {
        Log::warning($this->getMessage(), [
            'exception' => static::class,
            'book'      => $this->bookTitle,
            'user_id'   => auth()->id(),
        ]);
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error'   => 'book_not_available',
                'details' => ['book' => $this->bookTitle],
            ], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}