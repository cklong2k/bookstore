<?php

namespace App\Http\Controllers\Api\V100;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\V1\BookCollection;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Resources\V1\BookResource;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use App\Repositories\BookRepository;

class BookController extends Controller
{
    use HasApiTokens;
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books_cnt = Book::count();
        $books = Book::paginate((int) $request->size);

        // return BookResource::collection($books);
        return response()->json(
            new BookCollection($books, $books_cnt)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = new Book();
        $uuid = (string) Str::uuid();
        while (Book::find($uuid) !== null) {
            $uuid = (string) Str::uuid();
        }
        $bookData = [
            'id' => $uuid,
            'title' => $request->title,
            'author' => $request->author,
            'publicationDate' => $request->publicationDate,
            'category' => $request->category,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'images' => $request->images,
            'creator' => Auth::user()->id,
        ];
        try {
            $book = $this->bookRepository->createBook($bookData);
            return response()->json([
                'id' => $uuid
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(null, 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        try {
            $id = $book->id;
            $book = $this->bookRepository->findBook($id);
        } catch (\Throwable $th) {
            return response()->json(null, 404);
        }
        return response()->json(new BookResource($book));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $id = $book->id;
        $book = Book::find($id);
        if (empty($book)) {
            return response()->json(null, 404);
        }
        if ($book->creator != Auth::user()->id) {
            return response()->json(null, 400);
        }
        $bookData = [
            'title' => $request->title,
            'author' => $request->author,
            'publicationDate' => $request->publicationDate,
            'category' => $request->category,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'images' => $request->images,
        ];
        try {
            $book = $this->bookRepository->updateBook($id, $bookData);
            $log_str = sprintf("User:%d updated book %s", Auth::user()->id, $id);
            Log::info($log_str);
            return response()->json([
                'id' => $id
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(null, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $id = $book->id;
        $book = Book::find($id);
        if (empty($book)) {
            return response()->json(null, 404);
        }
        if ($book->creator != Auth::user()->id) {
            return response()->json(null, 400);
        }
        try {
            $book = $this->bookRepository->deleteBook($id);
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json(null, 400);
        }
    }
}
