<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Str;

/**
 * @group ControllerTest
 * @group AuthorControllerTest
 */
class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    private $baseUrl = '/api/v1';

    /**
     * @group testListOfAuthors
     */
    public function testListOfAuthors(): void
    {
        //fill the author data
        $author = Author::factory()->create([
            'name' => 'Name 123',
            'bio' => 'Bio',

        ]);

        $response = $this->json('GET', $this->baseUrl . '/authors');
        $response->assertStatus(200);
    }

    /**
     * @group testListOfAuthorsReturnEmpty
     */
    public function testListOfAuthorsReturnEmpty(): void
    {
        $response = $this->json('GET', $this->baseUrl . '/authors');
        $this->assertEquals($response->json()['data'], []);
        $response->assertStatus(200);
    }

    /**
     * @group testCreateAuthorReturnSuccess
     */
    public function testCreateAuthorReturnSuccess(): void
    {
        $name = Str::random(33);
        $bio = Str::random(33);
        $birthDate = date('Y-m-d');
        $payload = [
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ];

        Author::factory()->create($payload);

        $response = $this->json('POST', $this->baseUrl . '/authors', $payload);
        $response->assertStatus(200);
        $response->assertJsonFragment($response->json(), [
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ]);
    }

    /**
     * @group testCreateAuthorReturnInvalidName
     */
    public function testCreateAuthorReturnInvalidName(): void
    {
        $bio = Str::random(33);
        $birthDate = date('Y-m-d');
        $payload = [
            'name' => '',
            'bio' => $bio,
            'birth_date' => $birthDate,
        ];

        Author::factory()->create($payload);

        $response = $this->json('POST', $this->baseUrl . '/authors', $payload);
        $response->assertStatus(422);
        $this->assertEquals($response->json(), ['name' => [
            0 => 'The name field is required.'
        ]]);
    }

    /**
     * @group testCreateAuthorReturnInvalidBio
     */
    public function testCreateAuthorReturnInvalidBio(): void
    {
        $name = Str::random(33);
        $birthDate = date('Y-m-d');
        $payload = [
            'name' => $name,
            'bio' => '',
            'birth_date' => $birthDate,
        ];

        Author::factory()->create($payload);

        $response = $this->json('POST', $this->baseUrl . '/authors', $payload);
        $response->assertStatus(422);
        $this->assertEquals($response->json(), ['bio' => [
            0 => 'The bio field is required.'
        ]]);
    }

    /**
     * @group testUpdateAuthorReturnSuccess
     */
    public function testUpdateAuthorReturnSuccess(): void
    {
        $name = Str::random(33);
        $bio = Str::random(53);
        $birthDate = date('Y-m-d');

        //fill the author data
        $author = Author::factory()->create([
            'name' => $name,
            'bio' => $bio,
        ]);

        $payload = [
            'name' => $author->name,
            'bio' => $author->bio,
            'birth_date' => $birthDate,
        ];

        $response = $this->json('PUT', $this->baseUrl . '/authors/' . $author->id, $payload);
        $response->assertStatus(200);
        $response->assertJsonFragment($response->json(), [
            'name'  => $name,
            'bio'   => $bio,
        ]);
    }

    /**
     * @group testUpdateAuthorReturnValidationError
     */
    public function testUpdateAuthorReturnValidationError(): void
    {
        $name = '';
        $bio = '';
        $birthDate = date('Y-m-d');

        //fill the author data
        $author = Author::factory()->create([
            'name' => $name,
            'bio' => $bio,
        ]);

        $payload = [
            'name' => $author->name,
            'bio' => $author->bio,
            'birth_date' => $birthDate,
        ];

        $response = $this->json('PUT', $this->baseUrl . '/authors/' . $author->id, $payload);
        $response->assertStatus(422);
        $response->assertJsonFragment($response->json(), [
            'name' => [
                0 => 'The name field is required.',
            ],
            'bio' => [
                0 => 'The bio field is required.'
            ]
        ]);
    }

    /**
     * @group testDeleteAuthorReturnSuccess
     */
    public function testDeleteAuthorReturnSuccess() 
    {
        $id = \mt_rand(1111, 99999999);
        $name = Str::random(33);
        $bio = Str::random(53);
        $birthDate = date('Y-m-d');
        
        //fill the author data
        $author = Author::factory()->create([
            'id' => $id,
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ]);

        $response = $this->json('DELETE', $this->baseUrl . '/authors/'.$id);
        $response->assertStatus(200);
        $this->assertEquals($response->json(), true);
    }


    /**
     * @group testDeleteAuthorReturnFailed
     */
    public function testDeleteAuthorReturnFailed() 
    {
        $id = \mt_rand(1111, 99999999);
        $name = Str::random(33);
        $bio = Str::random(53);
        $birthDate = date('Y-m-d');
        
        //fill the author data
        $author = Author::factory()->create([
            'id' => (int) ($id + 1),
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ]);

        $response = $this->json('DELETE', $this->baseUrl . '/authors/'.$id);
        $response->assertStatus(404);
    }

    /**
     * @group testGetAllBookByAuthorReturnSuccess
     */
    public function testGetAllBookByAuthorReturnSuccess() 
    {
        $id = \mt_rand(1111, 99999999);
        $name = Str::random(33);
        $bio = Str::random(53);
        $birthDate = date('Y-m-d');
        
        //fill the author data
        $author = Author::factory()->create([
            'id' => (int) ($id + 1),
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ]);

        $book = Book::factory()->create([
            'author_id' => $author->id,
        ]);

        $response = $this->json('GET', $this->baseUrl . '/authors/'.$author->id . '/books');
        $response->assertStatus(200);
        $response->assertJsonFragment($response->json(), [
            'id' => $id,
            'name' => $name,
            'birth_date' => $birthDate,
            'books' => [
                0 => [
                    'id' => $book->id,
                ]
            ]
        ]);
    }

    /**
     * @group testGetAllBookByAuthorReturnEmpty
     */
    public function testGetAllBookByAuthorReturnEmpty() 
    {
        $id = \mt_rand(1111, 99999999);
        $name = Str::random(33);
        $bio = Str::random(53);
        $birthDate = date('Y-m-d');
        
        //fill the author data
        $author = Author::factory()->create([
            'id' => (int) ($id + 1),
            'name' => $name,
            'bio' => $bio,
            'birth_date' => $birthDate,
        ]);

        $book = Book::factory()->create([
            'author_id' => $author->id,
        ]);

        $response = $this->json('GET', $this->baseUrl . '/authors/'.$author->id + 1 . '/books');
        $response->assertStatus(200);
        $this->assertEquals($response->json(), []);
    }
}
