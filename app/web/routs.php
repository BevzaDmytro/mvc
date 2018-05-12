<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.05.2018
 * Time: 22:53
 */
//прописані рути
require_once "../../core/Router.php";

$r = new Router();
$r->addRoute("wares/{id}","WaresController@ware");

//$r->addRoute("^PHP_Fraimwork/(?<id>\d+)/(?<name>\d+)$","Main@index@(id,name)");

//TODO: rename to routes.php