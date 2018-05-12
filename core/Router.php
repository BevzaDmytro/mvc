<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.05.2018
 * Time: 14:05
 */
require_once 'C:\xampp\htdocs\mvc\app\controllers\WaresController.php';
class Router{
    private $routes = [];
    public function addRoute($path, $func, $middleware = null){
        //$path = preg_replace("/\\//", "\\/", $path);
        //$path = preg_replace("/\\^/", "/^\/", $path); //begin of string
        //$path = preg_replace("/\\$/", "\\/(\\?\S+?=\S+?)*?$/", $path); //end and query string

//        if(!preg_match("/\\$/", $path)) // if don't have end of string
//            $path = $path."\S*?\\/(\\?\S+?=\S+?)*?$/";

        $this->routes[$path] = preg_split("/\\@/", $func); // [class,func,args]

        //$this->routes[$path]["middleware"] = $middleware;
        $args = $this->routes[$path][2] ?? null;
        if ($args) {
            $args = preg_replace("/(\\(|\\))/", "", $args);
            $this->routes[$path][2] = preg_split("/,/", $args);
        }

    }

    public function run(){

        $path_from_user = $_SERVER["REQUEST_URI"];
        //echo "<hr> Путь из адресной строки:";
       // var_dump($path_from_user);
       // echo "<br>";
        foreach ($this->routes as $path => $view) {
            //$values_from_uri= [];
            $params_view_func = [];
           // echo "<hr> Путь из роутера :";
           // var_dump($path);
           // echo "<br>";
            //echo "Hello";
            //var_dump(preg_match_all("(.$path.)", $path_from_user, $values_from_uri));
            if (preg_match("@$path@i", $path_from_user, $values_from_uri)===1) {
                //var_dump($view[1]);
                //echo "Hello";
                /*
                if ($view[2] ?? false)
                    $params_view_func = $this->param_arr($view[2], $values_from_uri);
                */
                //var_dump($params_view_func);
                //var_dump($values_from_uri);
                $params_view_func = $values_from_uri[1];
                //var_dump($params_view_func);
                $req = call_user_func_array(array($view[0], $view[1]), array($params_view_func));

               // call_user_func_array()
                echo $req;
            }
        }
       // var_dump($this->routes);
    }

    private function param_arr($args, $reg_args){
        $values = [];
        foreach ($args as $arg) {
            $values[] = $reg_args[$arg];
            var_dump($arg);
        }
        return $values;
    }

}