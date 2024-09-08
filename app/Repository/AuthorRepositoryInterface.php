<?php
namespace App\Repository;

use App\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Blueprint for author repository
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
interface AuthorRepositoryInterface {
    /**
     * Blueprint for get Author
     * 
     * @return LengthAwarePaginator
     */
    public function getAuthor(): LengthAwarePaginator;

    /**
     * Blueprint for get author data by ID
     * 
     * @param int $id
     * 
     * @return Collection
     */
    public function getAuthorById(int $id): Collection;

    /**
     * Blueprint for store the author data
     * 
     * @param Request $request
     * 
     * @return Author
     */
    public function store(Request $request): Author;

    /**
     * Blueprint for update author data
     * 
     * @param Request $request
     * @param int $int
     * 
     * @return Author
     */
    public function update(Request $request, int $id): Author;

    /**
     * Blueprint for deletion of Author
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Blueprint for get all book by an author
     * 
     * @param int $authorId
     * 
     * @return Collection
     */
    public function getAllBookByAuthor(int $authorId);
}
