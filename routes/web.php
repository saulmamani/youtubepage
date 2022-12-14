<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return ["api" => "Bridge system to Youtube app Assuresoft, (Saul Mamani)"];
});

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get('search', 'VideoController@search');
    $router->get('channelVideos', 'VideoController@channelVideos');
    $router->get('forceChannelYoutubeVideos', 'VideoController@forceChannelYoutubeVideos');

    $router->get('playlistVideos', 'VideoController@playlistVideos');
    $router->get('videoDetail', 'VideoController@videoDetail');
    $router->get('comments', 'VideoController@comments');

});
