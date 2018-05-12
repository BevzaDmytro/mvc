<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.04.2018
 * Time: 20:12
 */
class PDOWrapper{
    private static $_instance;
    private $dbh;
    private $host = "localhost";
    private $dbname = "accounting";
    private $username = "root";
    private $password = "";


    public static function setInstance(){   //метод який створює о"єкт класу
        if (null === self::$_instance){      //якщо нема ще об"єктів тоді створити
            self::$_instance = new self();
        }
        return self::$_instance;             //якщо є - повернути існуючий
    }
    public function connect( $db_name, $driver = 'mysql', $host = 'localhost',
                             $charset = 'utf8', $db_user = 'root', $db_pass = '',
                             $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION], $debug = false){
        try {
            if($debug == false) {
                $this->db_name = $db_name;
                $this->dbh = new PDO("$driver:host=$host;dbname=$db_name;charset=$charset", $db_user, $db_pass, $options);
            }else{
                var_dump($db_name, $driver, $host,
                    $charset, $db_user, $db_pass,
                    $options);
                $this->dbh = new PDO("$driver:host=$host;dbname=$db_name;charset=$charset", $db_user, $db_pass, $options);
            }
        }catch(PDOException $exception){
            die('Не удалось подключиться к базе данных :(' . '<br>' . $exception->getMessage());
        }
    }

    public function SSQL($query, $placeholders = []){
        try {

                $result = $this->dbh->prepare($query);

                $result->execute($placeholders);

        }catch(PDOException $exception){
            echo 'Ошибка!' . $exception->getMessage() . '<br>';
        }
    }
    public function SQL($query, $placeholders = array()){
        try {
                $result = $this->dbh->prepare($query);

                $executed = $result->execute($placeholders);

                if($executed) {
                    return $result->fetchAll(PDO::FETCH_ASSOC);

            }
        }catch(PDOException $exception){
            echo 'Ошибка!' . $exception->getMessage() . '<br>';
        }
    }

    public function DSQL($query, $placeholders = []){
        try {
            $result = $this->dbh->prepare($query);

            $executed = $result->execute($placeholders);

            if($executed) {
                echo "<ul>";
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($row as $i => $val) {
                        echo "<li>|\t" . $i . "|\t" . $val . "|</li><br>";
                    }
                }
                echo "</ul>";
            }
        } catch (PDOException $exception) {
            echo 'Ошибка!' . $exception->getMessage();
        }
    }

}