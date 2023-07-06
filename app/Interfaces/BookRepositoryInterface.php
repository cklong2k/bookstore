<?php

namespace App\Interfaces;

interface BookRepositoryInterface
{
    public function findBook(string $bookId);
    public function createBook(array $bookDetails);
    public function updateBook(string $bookId, array $newDetails);
    public function deleteBook(string $bookId);
}