<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', 'AppController@home');

$router->get('/slack/authorize', 'SlackController@authorizeSlack');

$router->get('/slack/redirect', 'SlackController@redirect');

$router->post('/slack/archive', 'SlackController@archive');
