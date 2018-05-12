<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.04.2018
 * Time: 13:06
 */
require_once (".\core\Model.php");
class Customer extends Model{
    public function makeOrder($id_ware,$month,$number){
        MySQLBuilder::into("orders")->insert("id_customer","id_ware","month","number")->values($this->id,$id_ware,$month,$number)->run();
    }
}