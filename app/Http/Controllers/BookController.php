<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repository\BookRepository;
use App\Repository\BookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * This class used for Controller of Book
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
class BookController extends Controller
{

    /**
     * @var BookRepositoryInterface $interface
     */
    private BookRepositoryInterface $interface;

    /**
     * call the superclass
     * for handling user type auth
     */
    public function __construct(BookRepositoryInterface $interface, Request $request)
    {
        parent::__construct($request);
        $this->interface = $interface;
    }

    /**
     * Get list of books
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBooks(): \Illuminate\Http\JsonResponse
    {
        $result = $this->interface->getAllBook();
        return response()->json($result);
    }

    /**
     * Get book by ID
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookById(int $id): \Illuminate\Http\JsonResponse
    {
        $result = $this->interface->getBookById($id);
        return response()->json($result);
    }


    /**
     * Store new books to DD
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        //validate the input
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|string',
            'description'           => 'required|string',
            'publish_date'          => 'required|string',
            'author_id'             => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $save = $this->interface->store($request);
        return response()->json($save);
    }

    /**
     * Update the books by ID
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|string',
            'description'           => 'required|string',
            'publish_date'          => 'required|string',
            'author_id'             => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $save = $this->interface->update($request, $id);
        return response()->json($save);
    }

    /**
     * Delete the book by ID
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->interface->delete($id));
    }
}
