<?php
namespace App\Repository;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class AuthorRepository
 * 
 * This class for seperate businnes logic for Author entity
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
class AuthorRepository implements AuthorRepositoryInterface {

    /**
     * Get author data
     * 
     * @return LengthAwarePaginator
     */
    public function getAuthor(): LengthAwarePaginator 
    {
        return Author::whereNotNull('id')->paginate();
    }

    /**
     * Get Author by ID
     * 
     * @param int $id
     * 
     * @return Collection
     */
    public function getAuthorById(int $id): Collection 
    {
        $result = Author::with('books')->where('id', $id)->get();
        return !empty($result) ? $result : new Collection([]);
    }

    /**
     * Store new author to DB
     * 
     * @param Request $request
     * 
     * @return Author
     */
    public function store(Request $request): Author 
    {
        $author = new Author();
        $author->name = $request->name;
        $author->bio = $request->bio;
        $author->birth_date = $request->birth_date;
        $author->save();
        return $author;
    }

    /**
     * Update Author data by ID
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return Author
     */
    public function update(Request $request, int $id): Author 
    {
        $author = Author::find($id);
        $author->update([
            'name' => $request->name,
            'bio' => $request->bio,
        ]);

        if (Cache::has('book_author_'.$id)) {
            Cache::forget('book_author_'.$id);
        }

        return $author;
    }

    /**
     * Delete author data by ID
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $author = Author::findOrFail($id);
        return $author ? $author->delete() : null;
    }

    /**
     * Get all book data by Author
     * 
     * @param int $authorId
     * 
     * @return Collection
     */
    public function getAllBookByAuthor(int $authorId) 
    {
        $cacheExists = Cache::has('book_author_'.$authorId);
        if ($cacheExists) {

            $bookAuthor = Cache::get('book_author_'.$authorId);
            return $bookAuthor;
        }

        $result = Author::where('id', $authorId)->with('books')->get();
        return $result;
    }
}
