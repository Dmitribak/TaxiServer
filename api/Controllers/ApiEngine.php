<?php
require_once ('../api/model/PdoSql.php');
require_once ('../api/Configuration/ConstantAPI.php');

class APIEngine {

    private $apiControllersName;
    private $apiTypeMethod;
    private $params;

    //Статичная функция для подключения API из других API при необходимости в методах
    static function getApiEngineByName($apiName) {
        require_once 'apiBaseClass.php';
        require_once '../api/Controllers/'.$apiName.'Controllers.php';
        $apiClass = new $apiName();
        return $apiClass;
    }

    //Конструктор
    //$apiControllersName - название API и параметры в формате users/123/ad
    //$apiTypeMethod - тип запроса
    function __construct($apiControllersName, $apiTypeMethod) {
        $apiControllersName = preg_split('/\//', $apiControllersName);
        $apiControllersName = array_slice($apiControllersName, 2, count($apiControllersName) - 2);
        $this->params = array_slice($apiControllersName, 1, count($apiControllersName) -1 );
        $this->apiControllersName = $apiControllersName[0];
        $this->apiTypeMethod = $apiTypeMethod;
    }

    //Создаем JSON ответа
    //TODO: Реализовать ответ под каждый контроллер и метод
    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

    //Вызов функции по переданным параметрам в конструкторе
    function callApiFunction() {
        $resultFunctionCall = $this->createDefaultJson();//Создаем JSON  ответа
        $apiName = ucfirst($this->apiControllersName);//название контроллера меняем первую букву регистра
        if (file_exists('../api/Controllers/'.$apiName.'Controllers.php')) {


            $apiClass = APIEngine::getApiEngineByName($apiName);//Получаем название контроллера
            $apiReflection = new ReflectionClass($apiName);//Через рефлексию получем информацию о классе объекта
            $MethodName = $this->params[0];

            var_dump($MethodName);
            var_dump($apiReflection->getMethod($MethodName));
            try {
                //TODO: Разобраться с передачей параметров
                $MethodName = $this->params[0];//Название метода для вызова из контроллера
                $MethodType = $this->apiTypeMethod;//Тип запроса HTTP
                $apiReflection->getMethod($MethodName);//Провераем наличие метода

                echo getMethod($MethodName);

            } catch (Exception $ex) {
                //Непредвиденное исключение
                $resultFunctionCall->error = $ex->getMessage();
            }
        } else {
            //Если запрашиваемый API не найден
            $resultFunctionCall->errno = ConstantAPI::$ERROR_ENGINE_PARAMS;
            $resultFunctionCall->error = 'File not found';
            $resultFunctionCall->REQUEST = $_REQUEST;
        }
        return json_encode($resultFunctionCall);
    }
}