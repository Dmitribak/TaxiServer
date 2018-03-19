<?php
header('Content-type: text/html; charset=UTF-8');

if (count($_REQUEST)>0) {
    //TODO: Проверка токена, Проверка на пользователя


    require_once 'Controllers/ApiEngine.php';
    $type = $_SERVER['REQUEST_METHOD'];
    $url = $_SERVER['REQUEST_URI'];

    $ApiEngine = new APIEngine($url, $type);
    echo $ApiEngine->callApiFunction();

} else
{
    $jsonError->error='No function called';
    echo json_encode($jsonError);
}