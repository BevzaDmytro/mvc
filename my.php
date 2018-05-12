<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.04.2018
 * Time: 23:14
 */
require_once "app/Ware.php";
require_once "app/Customer.php";
require_once "core/View.php";
require_once "core/Router.php";

define("ROOT", dirname(__DIR__)."/mvc");

//echo "Hello<br>";
//$w = new Ware();
//$w->findId(2);
//echo $w->name;
//$w->name="JHDBFj";
//$w->update();

//$arr=["name_customer"=>"Customer"];
//$c = new Customer($arr);
//var_dump($c);
//$c->name_customer="Customer123981";
//$c->create();
//$c->name_customer="Me";
//$c->create();
//echo "<br>".$c->name_customer;
//echo $c->name_customer;
//var_dump($c);
//$c->makeOrder(1,1,9);
//$res=Customer::select()->where("id_customer","=",2)->exec();
//echo $res->name;
//var_dump($res);

//echo dirname(__DIR__);

//echo "<link rel='stylesheet' href='styles/styles.css'>";

/*$customer = Customer::select()->where("id_customer",">",0)->exec();
$v = new View();
foreach ($customer as $cu){
    //var_dump($cu);
    $s=$v->output("customer", $cu);
    //var_dump($s);
    //echo "<br>";
    echo $s;
}*/


/*
$ware = Ware::select()->where("id_ware",">",0)->exec();
$v = new View();
foreach ($ware as $w){
    //var_dump($cu);
    $s=$v->output("ware", $w);
    //var_dump($s);
    //echo "<br>";
    echo $s;
}
*/
//echo 'DSSFS';
$r = new Router();
$r->addRoute("/mvc/wares/([0-9]+)","WaresController@ware");
//$r->addRoute('/([a-z-]+)/([a-z-]+)/([0-9]+)',"WaresController@ware");

//$r->addRoute("/mvc/(?<id>d+)/(?<name>d+)","WaresController@ware");

//add_rout("^PHP_Fraimwork/(?<id>\d+)/(?<name>\d+)$","Main@index@(id,name)");
//$r->addRoute("mvc","WaresController@ware");
$r->run();