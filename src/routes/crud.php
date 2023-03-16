<?php

$this->app->router->group(['prefix' => 'api/test'], function(){
    $this->app->router->post('/','TestController@create');
    $this->app->router->get('/','TestController@all');
    $this->app->router->get('/{id}','TestController@get');
    $this->app->router->post('/auth','TestController@auth');
});