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

class BookController extends Controller
{
    use HasApiTokens;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
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
        $book->id = $uuid;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publicationDate = $request->publicationDate;
        $book->category = $request->category;
        $book->price = $request->price;
        $book->quantity = $request->quantity;
        $book->images = $request->images;
        $book->creator = Auth::user()->id;
        try {
            $book->save();
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
    public function show($id)
    {
        $book = Book::find($id);
        if (empty($book)) {
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
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::find($id);
        if (empty($book)) {
            return response()->json(null, 404);
        }
        if ($book->creator != Auth::user()->id) {
            return response()->json(null, 400);
        }
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publicationDate = $request->publicationDate;
        $book->category = $request->category;
        $book->price = $request->price;
        $book->quantity = $request->quantity;
        $book->images = $request->images;
        try {
            $book->save();
            return response()->json([
                'id' => $id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(null, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if (empty($book)) {
            return response()->json(null, 404);
        }
        if ($book->creator != Auth::user()->id) {
            return response()->json(null, 400);
        }
        try {
            $book->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json(null, 400);
        }
    }
}
