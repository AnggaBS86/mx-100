<?php

namespace App\Http\Controllers;

use App\Repository\AuthorRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

/**
 * Class AuthorController used for controller of module Author
 * 
 * @author Angga Bayu Sejati<anggabs86@gmail.com>
 */
class AuthorController extends Controller
{

    /**
     * @var AuthorRepositoryInterface $interface
     */
    private AuthorRepositoryInterface $interface;

    /**
     * call the superclass
     * for handling user type auth
     */
    public function __construct(AuthorRepositoryInterface $interface, Request $request)
    {
        parent::__construct($request);
        $this->interface = $interface;
    }

    /**
     * Get list of Authors
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAuthors(): \Illuminate\Http\JsonResponse
    {
        $result = $this->interface->getAuthor();
        return response()->json($result);
    }

    /**
     * Get Author by ID
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function getAuthorById(int $id): \Illuminate\Http\JsonResponse
    {
        $result = $this->interface->getAuthorById($id);
        return response()->json($result);
    }

    /**
     * Store new author to DB
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        //validate the input
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'bio'           => 'required',
            'birth_date'    => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $save = $this->interface->store($request);
        return response()->json($save);
    }

    /**
     * Update the author data
     * 
     * @param Request $request
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        //validate the input
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'bio'           => 'required',
            'birth_date'    => 'required|date'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $save = $this->interface->update($request, $id);
        return response()->json($save);
    }

    /**
     * Delete the author data by ID
     * 
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function delete(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->interface->delete($id));
    }

    /**
     * Get All Book data By Author
     * 
     * @param int $authorId
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function getAllBookByAuthor(int $authorId): \Illuminate\Http\JsonResponse
    {
        $result = $this->interface->getAllBookByAuthor($authorId);
        return response()->json($result);
    }

    /**
     * This is one of the example of performance tunning for better searching technique
     * this function call from Elasticsearch query search
     * 
     * Make sure the elasticsearch is installed and the data is ingested
     * 
     * Get all books data by author
     * 
     * @param int $authorId
     * 
     * @return mixed
     * 
     */
    public function getAllBookByAuthorElasticsearch(int $authorId)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('ELASTICSEARCH_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "query": {
                  "term": {
                    "_id": "'.$authorId.'"
                  }
                }
              }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ApiKey '.env('ELASTICSEARCH_API_KEY'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo ($response);
    }
}
