**MX-100 Job Portal API**

MX100 is job portal which connects company and expert freelancer/part-timer. It always keeps
track of freelancer performance and the more jobs the freelancer completes, the more
benefits they gain.

This REST API stack use PHP 8.2.7 and laravel 10 and MySQL 8.0.32

**Architecture Information**
- This project use repository based pattern
- The Repository pattern is used for abstracting how data is persisted or retrieved from a database. The idea behind the Repository pattern is to decouple the data access layer from the business access layer of the application so that the operations (such as adding, updating, deleting, and selecting items from the collection) is done through straightforward methods without dealing with database concerns such as connections, commands, and so forth.
<hr/>

**Bonus implementation :**

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

<hr/>

**How to installation** 

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

**REST API Documentation**

**1. Registration Endpoint**
- Endpoint : `{{URL}}/api/registration`
- Method : POST
- Parameter : 
```
{
		"name" : "name",
		"email" : "name@email.com",
		"user_type_id" : "2",
		"password" : "12345678"
}
```
- Response :
```
{
    "data": {
        "name": "proposal-name",
        "email": "name@email.com",
        "user_type_id": "2",
        "updated_at": "2023-07-06T06:26:09.000000Z",
        "created_at": "2023-07-06T06:26:09.000000Z",
        "id": 4
    },
    "access_token": "5|kA4yiO2TmoLcir2yl5FQRCduSMjadxc5Newl6Acq",
    "token_type": "Bearer"
}
```

**2. Login Endpoint**
- Endpoint : `{{URL}}/api/login`
- Method : POST
- Parameter : 
```
{
    "email" : "email@email.com",
    "password" : "12345678"
}
```
- Response : 
```
{
    "message": "Hi name12356789, welcome to home",
    "access_token": "2|QR6SydbrRi8H9yyr5pnVXoWLJGVfUIgRQVRAm9K3",
    "token_type": "Bearer"
}
```

**3. List of Proposal (By Employer)**
- Endpoint : `{{URL}}/api/jobs/proposal`
- Method : GET
- Parameter : -
- Header : 
```
Authentication Bearer {{access_token}}
```
- Response : 
```
[
    {
        "id": 1,
        "user_id": 2,
        "job_id": 1,
        "name": "Proposal11",
        "note": "Note 1",
        "created_at": "2023-07-05T18:17:14.000000Z",
        "updated_at": "2023-07-05T18:17:14.000000Z"
    },
    {
        "id": 2,
        "user_id": 1,
        "job_id": 1,
        "name": "Proposal 123",
        "note": "Notes",
        "created_at": "2023-07-05T20:25:46.000000Z",
        "updated_at": "2023-07-05T20:25:46.000000Z"
    }
]
```

**4. Create New Job Posting**
- Endpoint : `{{URL}}/api/jobs/create`
- Method : POST
- Parameter : -
- Header : 
```
Authentication Bearer {{access_token}}
```
- Parameter :
```
{
    "name" : "Job 123",
    "description" : "description ...",
    "status" : "draft" /*or "published" */
}
```

- Response :
```
{
    "user_id": 1,
    "name": "Job 123",
    "description": "description ...",
    "status": "draft",
    "updated_at": "2023-07-05T19:49:27.000000Z",
    "created_at": "2023-07-05T19:49:27.000000Z",
    "id": 3
}
```

**5. List of Published Jobs**
- Endpoint : `{{URL}}/api/proposal/published`
- Method : GET
- Parameter : -
- Header : 
```
Authentication Bearer {{access_token}}
```
- Parameter : -
- Response : 
```
[
    {
        "id": 1,
        "user_id": 1,
        "name": "lowongan PHP programmer",
        "description": "Lowongan Pekerjaan 1",
        "status": "published",
        "created_at": "2023-07-05T18:17:13.000000Z",
        "updated_at": "2023-07-05T18:17:13.000000Z",
        "proposal": [
            {
                "id": 1,
                "user_id": 2,
                "job_id": 1,
                "name": "Proposal11",
                "note": "Note 1",
                "created_at": "2023-07-05T18:17:14.000000Z",
                "updated_at": "2023-07-05T18:17:14.000000Z"
            }
        ]
    }
]
```
**6. Store new proposal**
- Endpoint : `{{URL}}/api/proposal/store`
- Method : POST
- Parameter : -
- Header : 
```
Authentication Bearer {{access_token}}
```
- Parameter : 
```
{
    "name" : "Proposal 1234",
    "job_id" : 2,
    "note" : "Notes"
}
```
- Response Success :
```
{
    "user_id": 1,
    "job_id": 2,
    "name": "Proposal 1234",
    "note": "Notes",
    "updated_at": "2023-07-06T07:00:49.000000Z",
    "created_at": "2023-07-06T07:00:49.000000Z",
    "id": 3
}
```

Response failed (proposal on refers job already exists) :
```
"Proposal already exists!"
```

<hr/>

**How to run unit testing**

- Run the command : 
```
./vendor/bin/phpunit ./tests --testdox
```
this is the example of unit test result :

```
mx-100 sudo ./vendor/bin/phpunit ./tests --testdox
[sudo] password for user: 
PHPUnit 10.2.3 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.7
Configuration: /var/www/html/mx-100/phpunit.xml

.............                                                     13 / 13 (100%)

Time: 00:12.369, Memory: 34.00 MB

Auth Controller (Tests\Feature\AuthController)
 ✔ Register new user succeed
 ✔ Register new user with email already exists
 ✔ Login should succeed
 ✔ Login should failed

Example (Tests\Feature\Example)
 ✔ The application returns a successful response

Example (Tests\Unit\Example)
 ✔ That true is true

Job Controller (Tests\Feature\JobController)
 ✔ List of proposal
 ✔ List of proposal with invalid user
 ✔ Create job
 ✔ Create job failed

Proposal Controller (Tests\Feature\ProposalController)
 ✔ Get published jobs
 ✔ Store proposal
 ✔ Store proposal failed

OK (13 tests, 22 assertions)
```

<hr/>