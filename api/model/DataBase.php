<?php

require_once '../api/Configuration/ConfigurationApi.php';
class DataBase extends ConfigurationApi
{
    private $DB_host;
    private $DB_name;
    private $DB_user;
    private $DB_password;
    public $DB;


    function __construct()
    {
        $this->DB_host = ConfigurationApi::DBHost;
        $this->DB_name = ConfigurationApi::DBName;
        $this->DB_user = ConfigurationApi::DBUser;
        $this->DB_password = ConfigurationApi::DBPassword;
        $this->openConnection();
    }

    public function openConnection(){
        try
        {
            $this->DB = new PDO('mysql:host='.$this->DB_host.';dbname='.$this->DB_name,$this->DB_user,$this->DB_password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DB->exec("set names utf8");
            echo "Connect on";
            return $this->DB;
        } catch (PDOException $e){
            echo 'Not Connect'. $e->getMessage();
            return $this->DB;
        }
    }


    public function closeDataBase(){
        $DB = null;
    }

    //Получить список чего либо
    //$what - что получить
    //$from - откуда получить
    public function getListWhat($what, $from, $where = null){
    }

    public function insertWhat($what = array(), $where){
        $DB = $this->DB;
        $stolbec = null;
        $stolb_name = null;
        foreach ($what as $key => $value){
            $stolbec = $stolbec.','.$key;
            $stolb_name = $stolb_name.','.$value;
        }
        $stolbec = substr($stolbec,1);
        $stolb_name = substr($stolb_name, 1);
        echo "<br>";
        print_r($stolbec);
        echo "<br>";
        print_r($stolb_name);
        echo "<br>";
        print_r($what);

        $stmt = $DB->prepare('INSERT INTO `'.$where.'`(`'.$stolbec.'`) VALUES ("'.$stolb_name.'")');
        $stmt->execute();
    }
}