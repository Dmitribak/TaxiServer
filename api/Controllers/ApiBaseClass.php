<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 19.03.2018
 * Time: 10:41
 */

class ApiBaseClass
{
    public $mySQLWorker=null;//Одиночка для работы с базой

    //Конструктор с возможными параметрами
    function __construct($dbName=null,$dbHost=null,$dbUser=null,$dbPassword=null) {
        if (isset($dbName)){//Если имя базы передано то будет установленно соединение с базой
            $this->mySQLWorker = DataBase::getInstance($dbName,$dbHost,$dbUser,$dbPassword);
            echo "Construct";
        }
    }

    function __destruct() {
        if (isset($this->mySQLWorker)){             //Если было установленно соединение с базой,
            $this->mySQLWorker->closeConnection();  //то закрываем его когда наш класс больше не нужен
        }
    }

    //Создаем дефолтный JSON для ответов
    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

    //Заполняем JSON объект по ответу из MySQLiWorker
    function fillJSON(&$jsonObject, &$stmt, &$mySQLWorker) {
        $row = array();
        $mySQLWorker->stmt_bind_assoc($stmt, $row);
        while ($stmt->fetch()) {
            foreach ($row as $key => $value) {
                $key = strtolower($key);
                $jsonObject->$key = $value;
            }
            break;
        }
        return $jsonObject;
    }
}