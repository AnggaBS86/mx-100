<?php
namespace App\Repository;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

/**
 * This class used for encapsulate businness logic for book entity
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
class BookRepository implements BookRepositoryInterface {

    /**
     * Get all book data and the paginate
     * 
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllBook(): LengthAwarePaginator 
    {
        return Book::whereNotNull('id')->paginate();
    }

    /**
     * Get book data by ID
     * 
     * @param int $id
     * @return Illuminate\Support\Collection
     */
    public function getBookById(int $id): Collection 
    {
        return Book::where('id', $id)->get();
    }

    /**
     * Store new book data to DB
     * 
     * @param Request $request
     * 
     * @return Book
     */
    public function store(Request $request): Book 
    {
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->publish_date = $request->publish_date;
        $book->author_id = $request->author_id;
        $book->save();
        return $book;
    }

    /**
     * Update book data and then save into DB
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return Book
     */
    public function update(Request $request, int $id): Book 
    {
        $book = Book::find($id);
        $book->update([
            'title' => $request->title,
            'description' => $request->description,
            'publish_date' => $request->publish_date,
            'author_id' => $request->author_id,
        ]);

        return $book;
    }

    /**
     * Delete book by ID
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $book = Book::findOrFail($id);
        return $book ? $book->delete() : null;
    }
}
