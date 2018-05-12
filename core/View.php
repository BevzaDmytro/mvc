<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.05.2018
 * Time: 16:34
 */
class View{
public function output($template,$dataArr=[]){
    //отримати ім"я файлу
    $filename = "C:\\xampp\htdocs\mvc\app\\views\\" ."$template". ".php";
    //$filename = $template . ".php";
    extract($dataArr, EXTR_SKIP);
   //початок буферизації
    ob_start();
    require $filename;
    return ob_get_clean();
}

public function render($params){
    //echo ROOT;
    $content = $params;
    require_once ROOT."/app/views/layouts/layout.php";
}
}