<?php
namespace App\Repository;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Blueprint for for Book Repository
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
interface BookRepositoryInterface {

    /**
     * Blueprint for Get All Book
     * @return LengthAwarePaginator
     */
    public function getAllBook(): LengthAwarePaginator;

    /**
     * Blueprint for get book by ID
     * 
     * @return Collection
     */
    public function getBookById(int $id): Collection;

    /**
     * Blueprint for store
     * 
     * @param Request $request
     * 
     * @return Book
     */
    public function store(Request $request): Book;

    /**
     * Blueprint for update book
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return Book
     */
    public function update(Request $request, int $id): Book;

    /**
     * Blueprint for deletion of book
     * 
     * @return bool
     */
    public function delete(int $id): bool;
}
