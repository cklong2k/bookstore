<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class BookTest extends TestCase
{
    use DatabaseTransactions;

    public function testBookCreate()
    {
        $user = User::factory(User::class)->create(); // Create a user

        $uuid = (string) Str::uuid();
        $bookData = [
            'id' => $uuid,
            'title' => 'The Amethyst Priestess',
            'author' => 'Norris',
            'publicationDate' => '2019-03-02',
            'category' => 'Fantasy',
            'price' => 500,
            'quantity' => 100,
            'images' => null,
            'creator' => $user->id
        ];

        $response = $this->actingAs($user)->post('api/v1/books', $bookData);

        $bookId = $response->json('id'); // Retrieve the book ID from the response

        $response->assertStatus(201); // Assuming successful registration redirects to another page

        $this->assertDatabaseHas('books', [
            'id' => $bookId,
        ]);
    }
}
