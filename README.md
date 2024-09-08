# MX-100 Library Management System REST API

MX100 library management system.

This REST API stack use PHP 8.2.7 and laravel 10 and MySQL 8.0.32

**Architecture Information**
- This project use repository based pattern
- The Repository pattern is used for abstracting how data is persisted or retrieved from a database. The idea behind the Repository pattern is to decouple the data access layer from the business access layer of the application so that the operations (such as adding, updating, deleting, and selecting items from the collection) is done through straightforward methods without dealing with database concerns such as connections, commands, and so forth.
<hr/>

# Performance Tuning :

**1. Using Native built up function from C languange**

I have develop PHP extension library, that used for email validation. The function name is : 
`bool validate_email(string $email)`

example usage : 
```
<?php
$email = "email@email.com";
$isEmailValid = validate_email($email); //return TRUE
print $isEmailValid;
?>
```

It is used C language for build this extension, you can see the detail project at https://github.com/AnggaBS86/validate-email-php-ext 
For this project, please build the extenstion first before you using this function by following the instruction from github above.


**2. Avoid N+1 query problem by using lazy loading**

I have implemented `Lazy Loading` to prevent the N+1 query problem
Basically, just using `with` function
example : 
```
  $result = Author::where('id', $authorId)->with('books')->get();
```

**3. Implement Cache**

```
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
```


**4. Implement Elasticsearch**

For the millions data we better use Elasticsearch for searching purpose. 
I've created the query indexer by using Golang and could be found at https://github.com/AnggaBS86/golang_elastic/ 

```
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
```

<hr/>

## How to installation

Since this tech stack using PHP and MySQL, i assumed you have been installed those 
Step by step installation : 
1. Installing PHP 8.0.x : https://linuxhint.com/install-php-8-ubuntu-22-04/ 
2. Installing MySQL : https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-22-04 
3. Installing composer : https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-22-04 

<hr/>

**How to run**

1. First we must import the database that used for unit testing purposed. The file is attached with this repository (the database name was "mx100_test")
2. Update the .env file, according to your configuration (especially DB credential)
3. Goto `mx-100` main dir, then run the command `composer install`
4. Run the database migration : `php artisan migrate` (if there is command for create new DB -> yes)
5. Run the `php artisan serve`

<hr/>

# REST API Documentation

## Author

**1. Get Author Endpoint**
- Endpoint : `{{URL}}/api/v1/authors`
- Method : GET
- Parameter : -
- Response :
```
{
    "current_page": 1,
    "data": [
        {
            "id": 2,
            "name": "Name Author 2 updated #3",
            "bio": "Biodata updated auhtor 2",
            "birth_date": "2000-11-11",
            "created_at": "2024-03-11T00:00:00.000000Z",
            "updated_at": "2024-09-07T06:55:39.000000Z"
        },
        {
            "id": 6,
            "name": "Name example 1",
            "bio": "Biodata",
            "birth_date": "2000-10-10",
            "created_at": "2024-09-06T10:16:02.000000Z",
            "updated_at": "2024-09-06T10:16:02.000000Z"
        },
        {
            "id": 14,
            "name": "Name 123",
            "bio": "Bio",
            "birth_date": "1975-01-31",
            "created_at": "2024-09-06T14:08:06.000000Z",
            "updated_at": "2024-09-06T14:08:06.000000Z"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/v1/authors?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/v1/authors?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/authors?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://127.0.0.1:8000/api/v1/authors",
    "per_page": 15,
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```

**2. Get Author By ID Endpoint**
- Endpoint : `{{URL}}/api/v1/authors/{id}`
- Method : POST
- Parameter :
- Response : 
```
[
    {
        "id": 2,
        "name": "Name Author 2 updated #3",
        "bio": "Biodata updated auhtor 2",
        "birth_date": "2000-11-11",
        "created_at": "2024-03-11T00:00:00.000000Z",
        "updated_at": "2024-09-07T06:55:39.000000Z",
        "books": [
            {
                "id": 2,
                "author_id": 2,
                "title": "Book Harry Potter 2",
                "description": "This is book of Harry Potter 2",
                "publish_date": "2000-10-10",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-09-06T12:51:07.000000Z"
            },
            {
                "id": 3,
                "author_id": 2,
                "title": "Book Three",
                "description": "desc",
                "publish_date": "2021-11-11",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-03-11T00:00:00.000000Z"
            },
            {
                "id": 4,
                "author_id": 2,
                "title": "Book OK",
                "description": "desc",
                "publish_date": "2021-11-11",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-03-11T00:00:00.000000Z"
            }
        ]
    }
]
```

**3. Get Author with Books**
- Endpoint : `{{URL}}/api/v2/authors/{authorId}/books`
- Method : GET
- Parameter : -
- Response : 
```
[
    {
        "id": 2,
        "name": "Name Author 2 updated #3",
        "bio": "Biodata updated auhtor 2",
        "birth_date": "2000-11-11",
        "created_at": "2024-03-11T00:00:00.000000Z",
        "updated_at": "2024-09-07T06:55:39.000000Z",
        "books": [
            {
                "id": 2,
                "author_id": 2,
                "title": "Book Harry Potter 2",
                "description": "This is book of Harry Potter 2",
                "publish_date": "2000-10-10",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-09-06T12:51:07.000000Z"
            },
            {
                "id": 3,
                "author_id": 2,
                "title": "Book Three",
                "description": "desc",
                "publish_date": "2021-11-11",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-03-11T00:00:00.000000Z"
            },
            {
                "id": 4,
                "author_id": 2,
                "title": "Book OK",
                "description": "desc",
                "publish_date": "2021-11-11",
                "created_at": "2024-03-11T00:00:00.000000Z",
                "updated_at": "2024-03-11T00:00:00.000000Z"
            }
        ]
    }
]
```

**4. Get Author with Book (from Elasticsearch)**
- Endpoint : `{{URL}}/api/v1/authors/{authorId}/books/elasticsearch`
- Method : GET
- Parameter : -
- Response :
```
{
    "took": 5,
    "timed_out": false,
    "_shards": {
        "total": 1,
        "successful": 1,
        "skipped": 0,
        "failed": 0
    },
    "hits": {
        "total": {
            "value": 1,
            "relation": "eq"
        },
        "max_score": 1.0,
        "hits": [
            {
                "_index": "mx_100_elastic",
                "_id": "2",
                "_score": 1.0,
                "_source": {
                    "id": 2,
                    "name": "Name Author 2 updated #3",
                    "bio": "Biodata updated auhtor 2",
                    "birth_date": "2000-11-11",
                    "books":  [
		            {
		                "id": 2,
		                "author_id": 2,
		                "title": "Book Harry Potter 2",
		                "description": "This is book of Harry Potter 2",
		                "publish_date": "2000-10-10",
		                "created_at": "2024-03-11T00:00:00.000000Z",
		                "updated_at": "2024-09-06T12:51:07.000000Z"
		            },
		            {
		                "id": 3,
		                "author_id": 2,
		                "title": "Book Three",
		                "description": "desc",
		                "publish_date": "2021-11-11",
		                "created_at": "2024-03-11T00:00:00.000000Z",
		                "updated_at": "2024-03-11T00:00:00.000000Z"
		            },
		            {
		                "id": 4,
		                "author_id": 2,
		                "title": "Book OK",
		                "description": "desc",
		                "publish_date": "2021-11-11",
		                "created_at": "2024-03-11T00:00:00.000000Z",
		                "updated_at": "2024-03-11T00:00:00.000000Z"
		            }
		        ]
                }
            }
        ]
    }
}
```

**5. Create New Author**
- Endpoint : `{{URL}}/api/v1/authors`
- Method : POST
- Parameter :
  ```
  {
    "name" : "Name example New",
    "bio" : "Biodata",
    "birth_date" : "2000-10-10"
  }
  ```
- Response : 
```
{
    "name": "Name example New",
    "bio": "Biodata",
    "birth_date": "2000-10-10",
    "updated_at": "2024-09-08T07:08:09.000000Z",
    "created_at": "2024-09-08T07:08:09.000000Z",
    "id": 15
}
```
- Response validation error :
  ```
  {
    "name": [
        "The name field is required."
    ]
  }
  ```
  
**6. Update Author**
- Endpoint : `{{URL}}/api/v1/authors`
- Method : PUT
- Parameter :
  ```
  {
    "name" : "Name Author 2 updated #3",
    "bio" : "Biodata updated auhtor 2",
    "birth_date" : "2000-10-10"
  }
  ``` 
- Response Success :
```
{
    "id": 2,
    "name": "Name Author 2 updated #3",
    "bio": "Biodata updated auhtor 2",
    "birth_date": "2000-11-11",
    "created_at": "2024-03-11T00:00:00.000000Z",
    "updated_at": "2024-09-07T06:55:39.000000Z"
}
```

Response failed (validation error) :
```
{
    "name": [
        "The name field is required."
    ]
}
```

**7. Delete Author**
- Endpoint : `{{URL}}/api/v1/authors/{id}`
- Method : DELETE
- Response Success :
```
true
```

## Book

**1. Get Book Endpoint**
- Endpoint : `{{URL}}/api/v1/books`
- Method : GET
- Parameter : -
- Response :
```
{
    "current_page": 1,
    "data": [
        {
            "id": 2,
            "author_id": 2,
            "title": "Book Harry Potter 2",
            "description": "This is book of Harry Potter 2",
            "publish_date": "2000-10-10",
            "created_at": "2024-03-11T00:00:00.000000Z",
            "updated_at": "2024-09-06T12:51:07.000000Z"
        },
        {
            "id": 3,
            "author_id": 2,
            "title": "Book Three",
            "description": "desc",
            "publish_date": "2021-11-11",
            "created_at": "2024-03-11T00:00:00.000000Z",
            "updated_at": "2024-03-11T00:00:00.000000Z"
        },
        {
            "id": 4,
            "author_id": 2,
            "title": "Book OK",
            "description": "desc",
            "publish_date": "2021-11-11",
            "created_at": "2024-03-11T00:00:00.000000Z",
            "updated_at": "2024-03-11T00:00:00.000000Z"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/v1/books?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/v1/books?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/books?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://127.0.0.1:8000/api/v1/books",
    "per_page": 15,
    "prev_page_url": null,
    "to": 3,
    "total": 3
}
```

**2. Get Book By ID**
- Endpoint : `{{URL}}/api/v1/books/{id}`
- Method : GET
- Parameter : -
- Response :
```
[
    {
        "id": 2,
        "author_id": 2,
        "title": "Book Harry Potter 2",
        "description": "This is book of Harry Potter 2",
        "publish_date": "2000-10-10",
        "created_at": "2024-03-11T00:00:00.000000Z",
        "updated_at": "2024-09-06T12:51:07.000000Z"
    }
]
```

**3. Create New Book**
- Endpoint : `{{URL}}/api/v1/books`
- Method : POST
- Parameter :
  ```
  {
    "title" : "Book Harry Potter",
    "description" : "This is book of Harry Potter",
    "publish_date" : "2000-10-10",
    "author_id" : 2
  }
  ```
- Response :
```
{
    "title": "Book Harry Potter",
    "description": "This is book of Harry Potter",
    "publish_date": "2000-10-10",
    "author_id": 2,
    "updated_at": "2024-09-08T07:37:14.000000Z",
    "created_at": "2024-09-08T07:37:14.000000Z",
    "id": 6
}
```

**4. Update Book By ID**
- Endpoint : `{{URL}}/api/v1/books/{id}`
- Method : PUT
- Parameter :
  ```
  {
    "title" : "Book Harry Potter 2",
    "description" : "This is book of Harry Potter 2",
    "publish_date" : "2000-10-10",
    "author_id" : 6
  }
  ```
- Response :
```
{
    "id": 6,
    "author_id": 6,
    "title": "Book Harry Potter 2",
    "description": "This is book of Harry Potter 2",
    "publish_date": "2000-10-10",
    "created_at": "2024-09-08T07:37:14.000000Z",
    "updated_at": "2024-09-08T07:38:00.000000Z"
}
```

**5. Delete Book By ID**
- Endpoint : `{{URL}}/api/v1/books/{id}`
- Method : DELETE
- Parameter : -
- Response :
```
true
```


<hr/>

# How to run unit testing

- Run the command : 
```
./vendor/bin/phpunit ./tests --testdox --group=ControllerTest
```
this is the example of unit test result :

```
➜  mx-100 git:(main) ✗ php artisan test --group=ControllerTest

   PASS  Tests\Feature\AuthorControllerTest
  ✓ list of authors                                                                                                                               0.35s  
  ✓ list of authors return empty                                                                                                                  0.01s  
  ✓ create author return success                                                                                                                  0.02s  
  ✓ create author return invalid name                                                                                                             0.01s  
  ✓ create author return invalid bio                                                                                                              0.01s  
  ✓ update author return success                                                                                                                  0.01s  
  ✓ update author return validation error                                                                                                         0.01s  
  ✓ delete author return success                                                                                                                  0.01s  
  ✓ delete author return failed                                                                                                                   0.01s  
  ✓ get all book by author return success                                                                                                         0.01s  
  ✓ get all book by author return empty                                                                                                           0.01s  

   PASS  Tests\Feature\BookControllerTest
  ✓ list of books                                                                                                                                 0.02s  
  ✓ list of books return empty                                                                                                                    0.01s  
  ✓ create book return success                                                                                                                    0.01s  
  ✓ create book return invalid title                                                                                                              0.01s  
  ✓ create book return invalid description                                                                                                        0.01s  
  ✓ update book return success                                                                                                                    0.01s  
  ✓ update book return validation error                                                                                                           0.01s  
  ✓ delete book return success                                                                                                                    0.01s  
  ✓ delete book return failed                                                                                                                     0.01s  

  Tests:    20 passed (59 assertions)
  Duration: 0.57s
```

<hr/>
