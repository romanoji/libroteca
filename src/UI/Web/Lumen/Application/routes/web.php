<?php
declare(strict_types=1);

use Laravel\Lumen\Application;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'books'], function (Application $app) {
    $app->get('', 'BookController@index');
    $app->get('/{id}', 'BookController@get');
    $app->post('', 'BookController@create');
    $app->put('/{id}', 'BookController@update');
});

$app->group(['prefix' => 'book_copies'], function (Application $app) {
    // TODO: "{id}/book_loans"
});

$app->group(['prefix' => 'readers'], function (Application $app) {
    // TODO:
});
