<?php


class Users
{
    public $DataBase = null;

    //http://www.example.com/api/?apitest.helloAPI={}
    function helloApiGet($param = null)
    {
        echo $param;
    }


    function registrationPhoneGet($param = null){
        if ($param)
        {
            $DB = new DataBase();
            $DB->insertWhat($param, users);


        }
//        else echo "Без параметров";
//
//        $stolbec = null;
//        $stolb_value = null;
//        foreach ($param as $key => $value){
//            $stolbec = $stolbec.', :'.$key;
//            $stolb_value = $stolb_value.', '.$value;
//        }
//        $stolbec = substr($stolbec,1);
//        $stolb_value = substr($stolb_value, 1);
//        echo '<br>';
//        echo $stolbec;
//        echo '<br>';
//        echo $stolb_value;


    }

    //http://www.example.com/api/?apitest.helloAPIWithParams={"TestParamOne":"Text of first parameter"}
    function helloAPIWithParams($apiMethodParams)
    {
        $retJSON = $this->createDefaultJson();
        if (isset($apiMethodParams->TestParamOne)) {
            //Все ок параметры верные, их и вернем
            $retJSON->retParameter = $apiMethodParams->TestParamOne;
        } else {
            $retJSON->errorno = APIConstants::$ERROR_PARAMS;
        }
        return $retJSON;
    }

    //http://www.example.com/api/?apitest.helloAPIResponseBinary={"responseBinary":1}
    function helloAPIResponseBinary($apiMethodParams)
    {
        header('Content-type: image/png');
        echo file_get_contents("http://habrahabr.ru/i/error-404-monster.jpg");
    }

    //Получить список всех пользователей
    function usersGet($param = null){
        echo 'Heello<br>';
        $db = new DataBase();

    }
}