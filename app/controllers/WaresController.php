<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.05.2018
 * Time: 9:09
 */
$path = $_SERVER['DOCUMENT_ROOT'];
require_once "C:\\xampp\htdocs\mvc\app\Ware.php";
require_once "C:\\xampp\htdocs\mvc\core\View.php";
class WaresController{
    public function ware($id){

        $v = new View();

        //переменная, в которой будет собираться текст вида, который надо вставить в шаблон
        $s = "";
        $ware = Ware::select()->where("id_ware",">=",$id)->exec();
        foreach ($ware as $w){

            //собираем текст вида
            $s .=$v->output("ware", $w);
        }

        //вставляем в шаблон
        $v->render($s);
    }
}