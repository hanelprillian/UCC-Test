# UCC Test

Its my result for fullfill the UnderCapital test requirement

## Tools

- Lumen
- VueJS
- Bootstrap View

## Installing Backend API

- Clone `git clone git@github.com:hanelprillian/UCC-Test.git`
- rename `.env.example` to `.env `
- run `composer install`
- create database called `ucctest`

## Run Backend
Run lumen by this command \
`php -S localhost:8000 -t public`

then migrate the database\
`php artisan migrate`

##Run Backend Unit Testing
Run testing by this command \
`phpunit` or `php vendor/phpunit/phpunit/phpunit`

## Installing Frontend

- goto folder `frontend`
- run command `npm install` to install dependencies
- run commit `npm run dev` 
- open `localhost:8080` on your browser

if you want to change BASE API in frontend, goto file `src\main.js` and change \
`window.BASE_API = 'http://localhost:8000/v1/'`

##Run Frontend Unit Testing
Run testing by this command \
`npm run test`
