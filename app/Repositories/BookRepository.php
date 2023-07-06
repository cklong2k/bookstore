<?php

namespace App\Repositories;

use App\Models\Book;
use App\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function createBook(array $data)
    {
        return Book::create($data);
    }

    public function updateBook(string $bookId, array $data)
    {
        $book = Book::findOrFail($bookId);
        $book->update($data);
        return $book;
    }

    public function deleteBook(string $bookId)
    {
        $book = Book::findOrFail($bookId);
        $book->delete();
    }

    public function findBook(string $bookId)
    {
        return Book::findOrFail($bookId);
    }
    
    // Add more methods as needed, such as fetching all books, searching, etc.
}