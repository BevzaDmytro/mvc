<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.04.2018
 * Time: 21:15
 */
require_once 'pdo.php';
class MySQLBuilder
{
    private $dbh;
    private static $table = null;
    private $query = null;
    private $select = null;
    private $from;
    private $where = null;
    private $insert = null;
    private $values;
    private $isInsert=false;
    private $update = null;
    private $isUpdate=false;
    private $delete=null;
    private $isDelete=false;
    private $join=null;
    private $on=null;
    private $placeholders=null;
    private static $into=null;
    private $isSelect=false;


    public function __construct(){
        $this->dbh = PDOWrapper::setInstance();
    }

    public function connect($db){
        $this->dbh->connect($db);
    }

    public static function table($name){
        MySQLBuilder::$table = $name;
        return new static;
    }

    public function join($anotherTable){
        if($this->join==null)
            $this->join .="JOIN ". $anotherTable;
        return $this;
    }
    public function on(string $field1, string $operator, string $value){
        if($this->on == null) {
            $this->on =" ON " . $field1 . " " . $operator . " " . $value . "";
            return $this;
        }else{
            $this->on .= ", ".$field1 . " " . $operator . " " . $value . "";
            return $this;
        }
    }
    public function select(... $fields){
        //if(MySQLBuilder::$table!=null) {$this->select="SELECT * FROM `". MySQLBuilder::$table . "`";$this->isSelect=true;}
        if(empty($fields)) {$this->select="SELECT * FROM `". MySQLBuilder::$table . "`";$this->isSelect=true;}
        else{
            $this->select="SELECT ";
            $i=0;
            while ($i<count($fields)-1){
                $this->select .= $fields[$i];
                $this->select .=",";
                $i++;
            }
            $this->select .= $fields[$i]. " ";
            if(MySQLBuilder::$table!=null) $this->select .= "FROM `". MySQLBuilder::$table . "`";
        }
        return $this;
    }
    public function from(... $fields){
        if(empty($fields)) $this->from="FROM `".MySQLBuilder::$table."`";
        else{
            $this->from="FROM ";
            $i=0;
            while ($i<count($fields)-1){
                $this->from .= $fields[$i];
                $this->from .=",";
                $i++;
            }
            $this->from .= $fields[$i];
        }
        return $this;
    }

    public function where(string $field1, string $operator, string $value){
        if($this->where == null) {
            $this->where =" WHERE " . $field1 . " " . $operator . " '" . $value . "'";
           // $this->placeholders[]=$value;
            return $this;
        }else{
            echo 'Для начала выберете таблицу(ы).';
        }
    }

    public function or(){
        if($this->where!=null) $this->where .= " OR ";
    }
    public function and(){
        if($this->where!=null) $this->where .= " AND ";
    }
    /*
    public function orderBy(string $field){
        $this->query = $this->query . " ORDER BY " . $field;
        return $this;
    }

*/
    public function insert(... $fields){
        $this->isInsert=true;
        $i = 0;
        $keys="";
        while ($i < count($fields) - 1)
        {
            $keys .= $fields[$i];
            $keys .=", ";
            $i++;
        }
        $keys .= $fields[$i];
        //var_dump($keys);
        if(MySQLBuilder::$into!=null)
        $this->insert = "INSERT INTO " . MySQLBuilder::$into . "(" . $keys . ")";
        else
            $this->insert = "INSERT INTO " . MySQLBuilder::$table . "(" . $keys . ")";
        //var_dump($this->insert);
        return $this;
    }
    public static function into($table){
        MySQLBuilder::$into .=$table;
        return new static;
    }

    public function values(... $fields){
        $i = 0;
        $keys="'";
        while ($i < count($fields) - 1)
        {
            $keys .= $fields[$i];
            $keys .= "', '";
            //$keys .="?, ";
            $i++;
            $this->placeholders[]=$fields[$i];
        }
        $keys.= $fields[$i];
       // $keys .="?";
        $this->placeholders[]=$fields[$i];
        //$this->placeholders[]=$this->id;
        //var_dump($keys);

        $this->values = " VALUES(" . $keys . "');";
        //var_dump($this->values);
        return $this;
    }

    public function update($field, $value){
        $this->isUpdate=true;
        if($this->update==null)
            $this->update = "UPDATE " . MySQLBuilder::$table . " SET " . $field . "='" . $value . "'";
        else
            $this->update .= ",". $field . "='" . $value . "'";
       // $this->placeholders[]=$value;

        return $this;
    }

    public function delete(){
        $this->isDelete=true;
        $this->delete = "DELETE FROM `" . MySQLBuilder::$table . "`";
        return $this;
    }


    public function exec($placeholders = []){
        if($this->select!=null) $this->query=$this->select;
        if($this->isSelect==false) $this->query .=$this->from;
        if($this->where!=null) $this->query .=$this->where;
        if($this->join!=null){
            $this->query .=$this->join . $this->on;
        }
        //var_dump($this->query);
      return  $this->dbh->SQL($this->query, $placeholders);

    }
    public function run($placeholders = []){
        if($this->isInsert==true) {$this->query .= $this->insert;
        $this->query .= $this->values;}
        if($this->isUpdate==true)
        {$this->query .= $this->update;
        $this->query .= $this->where;
        }
        if($this->isDelete==true){
            $this->query .= $this->delete;
            $this->query .= $this->where;
        }
        //var_dump($this->query);
        //var_dump($this->placeholders);
        $this->dbh->SSQL($this->query, $placeholders);
        unset($this->placeholders);
    }

}