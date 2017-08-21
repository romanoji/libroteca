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

    $app->group(['prefix' => '{bookID}/copies'], function (Application $app) {
        $app->get('', 'BookCopyController@index');
        $app->post('', 'BookCopyController@create');
        $app->put('/{id}', 'BookCopyController@update');
    });
});

$app->group(['prefix' => 'book_loans'], function (Application $app) {
    $app->get('', 'BookLoanController@index');
    $app->get('/{id}', 'BookLoanController@get');
    $app->post('', 'BookLoanController@create');
    $app->put('/{id}', 'BookLoanController@update');
});

$app->group(['prefix' => 'readers'], function (Application $app) {
    $app->get('', 'ReaderController@index');
    $app->get('/{id}', 'ReaderController@get');
    $app->post('', 'ReaderController@create');
});
