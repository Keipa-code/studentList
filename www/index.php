<?php

require_once __DIR__ . '/vendor/autoload.php';

use Src\Model\SearchValidator;
use Src\Model\Pager;
use Src\Model\ListsData;
use Src\Model\LinkGenerator;
use Src\Model\Tdgw;
use Src\Model\MainPage;
use Src\Model\Validator;

error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors',1);
error_reporting(-1);

$pageLink = new LinkGenerator;
$conn = new Tdgw;
$pager = new Pager();
$validator = new Validator;

$errors = $validator->validateDataFromGET($_GET);
if (isset($errors)){
    $_GET = null;
    $_GET = array();
}




$mainPage = new MainPage($_GET, $_COOKIE);
//$listsData = new ListsData();
$listsData = $conn->fetchLineByGet($_GET, 50);
$pager->getCurrentPageNumber($_GET);


$totalRowCount = $conn->getTotalUsersCount();
$pageCount = $pager->totalPagesCount($conn->fetchLineByGet($_GET, $totalRowCount['0']));

$pager->getPageNumbers($pager->currentPageNumber, $pageCount);

require_once ('index.html');