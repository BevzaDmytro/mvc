<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.04.2018
 * Time: 13:05
 */
require_once ('E:\2 курс (4 семестр)\Веб програмування\MVC\core\MySQLBuilder.php');
class Model{
    private $table;
    private $attributes=[];//запис с БД в вигляді хеш-масиву
    private $idField;
    protected $id;
    private $isChanged;
    private $isAutoInc=true;
    private $isNew=true;
    private $builder;

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } else {
            return "Field does not exist";
        }
    }
    public function __set($name, $value) {
        $this->isChanged[]=$name;
        if (isset($this->attributes[$name])) {
            $this->attributes[$name] = $value;
        }
        else echo "Field not exist";
        if(empty($this->attributes)){
            $this->attributes=[$name => $value];
        }
    }

    public function setTable(){
        //отримує назву таблиці. Перевіряє чи тейбл вже вказано. Рефлексія
         $res = get_called_class();
         $this->idField="id_".strtolower($res);
         $res=strtolower($res)."s";
         $this->table=$res;
        // User->users
    }
    public function __construct($attr=null,$isNew=true)
    {
        $this->setTable();
        $this->builder = new MySQLBuilder();
       $this->builder->connect("accounting");
        $this->builder=MySQLBuilder::table($this->table);
        if($attr!=null) $this->attributes=$attr;
    }
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        // $us=User::where(age > 16)->get(), foreach($us as $u){...}. Get поверне масив обьєктів Юзер
        $res=call_user_func_array(array($this->builder,$name),$arguments);
        if($res instanceof MySQLBuilder) return $this;
        return $res;
    }
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $model = new static();
        $res = call_user_func_array(array($model,$name),$arguments);
        return $res;
    }

    public function findId($id){

        $res = MySQLBuilder::table($this->table)->select()->where("$this->idField","=",$id)->exec();
        foreach ($res as $row) {
                $this->attributes = $row;
        }
        $this->id=$id;
        //var_dump($res);
        //var_dump($this->attributes);
        //var_dump($this->table);
        //return $res;
    }

    public function create()
    {
        if($this->isNew==false) $this->update();
        else {
            $this->isNew = false;
            $keys = "";
            $values = "";
            foreach (array($this->attributes) as $row) {
                //echo $row;
                foreach ($row as $key => $value) {
                    if (!is_int($key)) {
                        $keys .= $key . ',';
                        $values .= $value . ',';
                    }
                }
            }
            $keys = substr($keys, 0, -1);
            $values = substr($values, 0, -1);
            MySQLBuilder::table($this->table)->insert($keys)->values($values)->run();
            $res = MySQLBuilder::table($this->table)->select($this->idField)->exec();
            //var_dump($res);
            foreach ($res as $row) {
                foreach ($row as $key => $value) {
                    if ($key == $this->idField) $this->id = $value;
                }
            }
        }
    }
    public function update(){
        foreach (array($this->attributes) as $row) {
            foreach ($row as $key => $value) {
               foreach ($this->isChanged as $key1=>$value1) {
                    if($value1==$key)  MySQLBuilder::table($this->table)->update($key,$value)->where("$this->idField","=","$this->id")->run();
               }
                }
            }
         }
    public function delete(){
        MySQLBuilder::table($this->table)->delete()->where("$this->idField","=","$this->id")->run();
    }
}