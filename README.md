## Requirements
This project is built on top of laravel 8 <br />

You need to have docker installed or alternatively check the server requirements below

## Server Requirements

- PHP >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Clone Project

- git clone https://github.com/jadKhoury1/recipe-ordering-system.git
- you can clone the project in any directory

## Create Environment Files

- Create a .env file from the .env.example file
- Create a .env.testing file which will be used as the testing environment

## Run Docker

- run **docker-compose build** command
- run **docker-compose up** command

## Running Services
- mysql service: A mysql container should be running and forwarding to port 3306
- redis service: A redis container should be running and forwarding to port 6379

## Running Containers
Now docker should be up and running. We can check the running containers by executing the following command <br />
**docker ps**: You will be able to see the running container IDs. <br /> <br />


## Run Commands In Container
To be able to run commands inside the container, run the following command
*docker exec -it {APP_ID} bash* <br />

We now accessed the app container,and we will run the start.sh script that will initialize the database,
**sh start.sh**  <br />

The script will create the databases, run the migrations and seeds <br />

Additionally an access token will be generated

## Access Tokens
All the endpoints require an authorization, this is why you need to include the previously generated token
in the **Authorization** header as a *Bearer* token on each request <br />

If you wish to generate other tokens, you can run the command **php artisan token:generate {name}** <br />

In case you ran the command twice for the same name, a new token will be generated for that name

## Test the endpoints
To check if the project is running successfully, write http://localhost in the browser. You should see a page with the 
laravel logo <br />

To be able to run the unit tests, you need to access the **App** container and execute the following command <br>
**php artisan test --testsuite=Feature** <br />

After running the command you will see all the test cases being executed

## Postman
You can import the following postman collection https://www.getpostman.com/collections/66a9e9c0f86ccf3cff4e to view and test the APIs <br />

*Dont't forget to sent the Bearer token or the API will not be accessible*
