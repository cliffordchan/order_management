<?php

// @todo Use API versioning for graceful improvement
//$router->group(['prefix'=>'api/v1'], function() use($router){
$router->get('orders', 'OrderController@listOrders');
$router->post('order', 'OrderController@createOrder');
$router->put('order/{id:[\d]+}', 'OrderController@updateOrder');
//});