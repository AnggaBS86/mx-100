<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Author;
use App\Models\Book;
use Exception;
use Illuminate\Support\Str;

/**
 * @group ControllerTest
 * @group BookControllerTest
 */
class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    private $baseUrl = '/api/v1';

    /**
     * @group testListOfBooks
     */
    public function testListOfBooks(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        //fill the book data
        Book::factory()->create([
            'title' => 'title of the book',
            'description' => 'Desc',
            'publish_date' => date('Y-m-d'),
            'author_id' => $author->id,
        ]);

        $response = $this->json('GET', $this->baseUrl . '/books');
        $response->assertStatus(200);
    }

    /**
     * @group testListOfBooksReturnEmpty
     */
    public function testListOfBooksReturnEmpty(): void
    {
        $response = $this->json('GET', $this->baseUrl . '/books');
        $this->assertEquals($response->json()['data'], []);
        $response->assertStatus(200);
    }

    /**
     * @group testCreateBookReturnSuccess
     */
    public function testCreateBookReturnSuccess(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = Str::random(33);
        $description = Str::random(33);
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];

        $response = $this->json('POST', $this->baseUrl . '/books', $payload);
        $response->assertStatus(200);
        $response->assertJsonFragment($response->json(), [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ]);
    }

    /**
     * @group testCreateBookReturnInvalidTitle
     */
    public function testCreateBookReturnInvalidTitle(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = '';
        $description = Str::random(33);
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];

        $response = $this->json('POST', $this->baseUrl . '/books', $payload);
        $response->assertStatus(422);
        $this->assertEquals($response->json(), ['title' => [
            0 => 'The title field is required.'
        ]]);
    }

    /**
     * @group testCreateBookReturnInvalidDescription
     */
    public function testCreateBookReturnInvalidDescription(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = Str::random(33);
        $description = '';
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];

        $response = $this->json('POST', $this->baseUrl . '/books', $payload);
        $response->assertStatus(422);
        $this->assertEquals($response->json(), ['description' => [
            0 => 'The description field is required.'
        ]]);
    }

    /**
     * @group testUpdateBookReturnSuccess
     */
    public function testUpdateBookReturnSuccess(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = Str::random(33);
        $description = Str::random(33);
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];
        $book = Book::factory()->create($payload);

        $response = $this->json('PUT', $this->baseUrl . '/books/' . $book->id, $payload);
        $response->assertStatus(200);
        $response->assertJsonFragment($response->json(), [
            'title'  => $title,
            'description'   => $description,
        ]);
    }

    /**
     * @group testUpdateBookReturnValidationError
     */
    public function testUpdateBookReturnValidationError(): void
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = '';
        $description = '';
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];
        $book = Book::factory()->create($payload);

        $response = $this->json('PUT', $this->baseUrl . '/books/' . $book->id, $payload);
        $response->assertStatus(422);
        $this->assertEquals($response->json(), [
            'title' => [
                0 => 'The title field is required.'
            ],
            'description' => [
                0 => 'The description field is required.'
            ]
        ]);
    }

    /**
     * @group testDeleteBookReturnSuccess
     */
    public function testDeleteBookReturnSuccess()
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = Str::random(33);
        $description = Str::random(33);
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];
        $book = Book::factory()->create($payload);

        $response = $this->json('DELETE', $this->baseUrl . '/books/' . $book->id);
        $response->assertStatus(200);
        $this->assertEquals($response->json(), true);
    }


    /**
     * @group testDeleteBookReturnFailed
     */
    public function testDeleteBookReturnFailed()
    {
        $author = Author::factory()->create([
            'name' => 'Name of 123',
            'bio' => 'bio',
            'birth_date' => date('Y-m-d'),
        ]);

        $title = Str::random(33);
        $description = Str::random(33);
        $publishDate = date('Y-m-d');
        $payload = [
            'title' => $title,
            'description' => $description,
            'publish_date' => $publishDate,
            'author_id' => $author->id,
        ];
        $book = Book::factory()->create($payload);

        $response = $this->json('DELETE', $this->baseUrl . '/books/' . $book->id + 1);
        $response->assertStatus(404);
    }
}
