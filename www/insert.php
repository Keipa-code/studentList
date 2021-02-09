<?php

require_once __DIR__ . '/vendor/autoload.php';
use Src\Model\Tdgw;
use Src\Model\PdoSet;
use Src\Model\Student;
use Src\Model\Validator;
use Src\Model\PasswordGenerator;
use Src\Model\RegisterPage;

error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors',1);
error_reporting(-1);

$conn = new Tdgw;
$validator = new Validator;
$passGenerator = new PasswordGenerator;
$pdoSet = new PdoSet();
$token = $passGenerator->generatePassword(32);
setcookie('token', $token, time() +3600);

//60*60*24*365*10
//var_dump($_COOKIE);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_COOKIE['token']) || $_POST['token'] !== $_COOKIE['token']){
        $error = 'Данные отклонены';
        return;
    }
    $student = new Student($_POST);
    $student->fillStudentDataFromPost($_POST);
    $allowed = array("firstName","surName", "gender", "groupNumber", "email", "points", "birthYear", "location", "passwd");

    if (htmlspecialchars($_POST['submittype']) == 'Обновление'){

        $student->student['id'] = $_POST['id'];
        $student->student['passwd'] = $_POST['passwd'];

        $conn->update($pdoSet->pdoSetter($allowed),$student->student);
        $cookieID = $student->student['id'];
    }elseif(htmlspecialchars($_POST['submittype']) == 'Регистрация'){
        var_dump($student->student);
        $conn->insert($pdoSet->pdoSetter($allowed), $student->student);
        $cookieID = $conn->conn->lastInsertId();

    }

    $cookiePass =$student->student['passwd'];
    setcookie("id", $cookieID, time() +3600);
    setcookie("passwd", $cookiePass, time() +3600);
    header("Location: /insert.php?notify=registered");

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $page = new RegisterPage;
    $data = array();
    if (isset($_GET['notify'])){
        $message = 'Регистрация завершена успешно';
    }

    if (isset($_COOKIE['id'])){
        $error = $validator->validateCookie($_COOKIE);
    }
    if (!isset($error) && isset($_COOKIE['id'])){

        $allowed = array("id","passwd");
        $page->fillCookie($_COOKIE);

        $data = $conn->findByIDAndPassword($pdoSet->pdoSetterWithAnd($allowed), $page->cookie);
        if ($data !== false){
            $page->putData($data);
        }
        //var_dump($page->radio['genderWoman']);
        $submitType = 'Обновление';
    }
    $submitType = 'Регистрация';
}

require_once ('insert.html');


