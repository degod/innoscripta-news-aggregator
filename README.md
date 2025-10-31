## INTRODUCTION

This is a skills and experience assessment for the role of a Backend Web Developer at Innoscripta.

## THE CHALLENGE

The challenge is to build the backend functionality for a news aggregator website that pulls articles from various sources and serves them to the frontend application.

## PRE-REQUISITE FOR SETUP

-   Docker desktop
-   Web browser (to view swaggerUI API documentation)
-   Terminal (git bash)

## HOW TO SETUP

-   Make sure your docker desktop is up and running
-   Launch you terminal and navigate to your working directory

```bash
cd ./working_dir
```

-   Clone repository

```bash
git clone https://github.com/degod/innoscripta-news-aggregator.git
```

-   Move into the project directory

```bash
cd innoscripta-news-aggregator/
```

-   Copy env.example into .env

```bash
cp .env.example .env
```

-   Build app using docker

```bash
docker compose up -d --build
```

-   Log in to docker container bash

```bash
docker compose exec app bash
```

-   Install composer

```bash
composer install
```

-   Create an application key

```bash
php artisan key:generate
```

-   Create an JWT secret key

```bash
php artisan jwt:secret
```

-   Run database migration and seeder

```bash
php artisan migrate:fresh --seed
```

## Running the application job and testing

-   Run the fetch command to get latest news (kindly use keys sent via email to populate provider keys in .env first)

```bash
php artisan news:fetch
```

-   Or keep a seperate tab open to auto-fetch latest news (make sure keys are intact in .env)

```bash
php artisan schedule:work
```

-   Just to check if all is fine using the test

```bash
php artisan test
```

## Accessing the API docs and database

-   To access application, visit
    `http://localhost:9020`

-   To access swaggerUI documentation, visit
    `http://localhost:9020/api/docs`

-   To Login in documentation (or with POSTman - make sure to follow docs instructions):

    -   `U:  admin@example.com`
    -   `P:  password`

-   To access application's database, visit
    `http://localhost:9021`

## Contributing

Please open issues or pull requests against this repository. Follow existing code style and update tests where appropriate.

---

If you want, I can also add a short troubleshooting section based on any errors you see when trying these steps locally. What would you like me to add or change in this README?
