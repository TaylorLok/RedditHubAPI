# Project Title

RedditAPI

Reddit is a social news aggregation, web content rating, and discussion website. It consists of a network of communities, called "subreddits," where users can dive into their interests, hobbies, and passions.

## Installation

1. Clone the repository: `git clone https://github.com/TaylorLok/RedditHubAPI.git`
2. Navigate to the project directory: `cd RedditHubAPI`
3. Install dependencies: `composer install`
4. Add your .env file and run: `php artisan migrate`
5. Seed the database with sample data(User and Post) by running the following command: `php artisan db:seed`
6. Run the server: `php artisan serve`


## Test user login credentials
1. email: `admin@test.com`
2. Password: `password`

## Usage

## API Endpoints

## Autentcation endpoint
Register into the system and login to get you bearer token to use to access the ressources

1. Register: http://127.0.0.1:8000/api/register?
2. Login: http://127.0.0.1:8000/api/login?


## Post endpoint
1. Get all posts `http://127.0.0.1:8000/api/posts`
2. Get post by id `http://127.0.0.1:8000/api/posts/4`
3. Create a post: `http://127.0.0.1:8000/api/posts/create`
4. Find post by username: `http://127.0.0.1:8000/api/user/Admin Test/posts`
5. Update a post by id: `http://127.0.0.1:8000/api/posts/update/10`
6. Delete a post by id: `http://127.0.0.1:8000/api/posts/delete/9`


