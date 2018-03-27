<?php
require_once ('../api/model/DataBase.php');
require_once ('../api/Configuration/ConstantAPI.php');

class APIEngine {

    private $apiControllersName;
    private $apiTypeMethod;
    private $params;

    //Статичная функция для подключения API из других API при необходимости в методах
    static function getApiEngineByName($file_name, $controllerName) {
        require_once $file_name;
        $apiClass = new $controllerName();
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

    function paramsGet($paramMethods){
            $parametrs = preg_split("/&/", substr($paramMethods[0], 1));
            $params = array();

            for ($i=0; $i<count($parametrs);$i++){
                $arr = preg_split('/=/', $parametrs[$i]);
                $params[$arr[0]] = $arr[1];
            }
            return $params;
    }

    //Вызов функции по переданным параметрам в конструкторе
    function callApiFunction(){
        $controllerName = ucfirst($this->apiControllersName);//В названии контроллера меняем первую букву
        $file_name = '../api/Controllers/'.$controllerName.'Controllers.php';
        if (file_exists($file_name)) {
            $apiClass = APIEngine::getApiEngineByName($file_name, $controllerName);
            $typeMethods = $this->apiTypeMethod; //Получаем тип метода
            if (substr($this->params[0],0,1)=="?"){
                $methodName = null;
                echo 'methodName = null<br>';
                $paramMethods = $this->params;
            } else {
                $methodName = array_shift($this->params);//Получаем имя метода
                $paramMethods = $this->params;
            }
            if ($methodName == null)
                $methodName = lcfirst($controllerName);

            if ($paramMethods!= null){

                $params = APIEngine::paramsGet($paramMethods);

                switch ($typeMethods){
                case 'GET':
                    $methodName = $methodName."Get";
                    $apiClass = $controllerName::$methodName($params);
                    break;
                case 'POST':
                    $methodName = $methodName."Post";
                    $apiClass = $controllerName::$methodName($params);
                    break;
                case 'DELETE':
                    $methodName = $methodName."Delete";
                    $apiClass = $controllerName::$methodName($params);
                    break;
                case 'PUT':
                    $methodName = $methodName."Put";
                    $apiClass = $controllerName::$methodName($params);
                    break;
                default:
                    $callJson->error = 'Method don`t have';
            }
            } else {
                switch ($typeMethods){
                    case 'GET':
                        $methodName = $methodName."Get";
                        $apiClass = $controllerName::$methodName();
                        break;
                    case 'POST':
                        $methodName = $methodName."Post";
                        $apiClass = $controllerName::$methodName();
                        break;
                    case 'DELETE':
                        $methodName = $methodName."Delete";
                        $apiClass = $controllerName::$methodName();
                        break;
                    case 'PUT':
                        $methodName = $methodName."Put";
                        $apiClass = $controllerName::$methodName();
                        break;
                    default:
                        $callJson->error = 'Method don`t have';
                }
            }
        } else {
            $callJson->error = 'File Controller not found';
            $callJson->path = $file_name;
        }
    }






    function callApiFunctionsss() {
        $resultFunctionCall = $this->createDefaultJson();//Создаем JSON  ответа
        $apiName = ucfirst($this->apiControllersName);//название контроллера меняем первую букву регистра
        if (file_exists('../api/Controllers/'.$apiName.'Controllers.php')) {


            $apiClass = APIEngine::getApiEngineByName($apiName);//Получаем название контроллера
            $apiReflection = new ReflectionClass($apiName);//Через рефлексию получем информацию о классе объекта
            $MethodName = $this->params[0];

            var_dump($MethodName);
            echo "<br>";
            var_dump($apiReflection->getMethod($MethodName));
            try {
                //TODO: Разобраться с передачей параметров
                $MethodName = $this->params[0];//Название метода для вызова из контроллера
                $MethodType = $this->apiTypeMethod;//Тип запроса HTTP
                $apiReflection->getMethod($MethodName);//Провераем наличие метода


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