<?php
namespace Src\Model;

use PDO;

class Tdgw
{
    public $conn = null; // defining the property 'helper' with a literal

    public function __construct()
    {
        /*$user     = 'root';
        $pass     = 'Region&50';
        $db       = 'student';
        $host     = '192.168.20.11';*/
        $config = parse_ini_file("../src/config.ini");
        $charset  = 'utf8';
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db'].';charset='.$charset;
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,

        ];
        $this->conn = new PDO($dsn, $config['user'], $config['pass'], $opt);
    }

   public function insert($pdoSet, $student)
    {

        $sql = "INSERT INTO abiturients SET " . $pdoSet;
        //var_dump($student);
        //var_dump($pdoSet);
        $stm = $this->conn->prepare($sql);
        $stm->execute($student);
    }

    public function update($pdoSet, $student){
        $sql = "UPDATE abiturients SET ".$pdoSet." WHERE id = :id";
        $stm = $this->conn->prepare($sql);
        //var_dump($student);
        $stm->execute($student);
    }

    public function fetchLine($page)
    {
        $perPage = 50;
        $limitFrom = $page *50 - 50;
        $stm = $this->conn->prepare('SELECT id, firstName, surName, groupNumber, points FROM abiturients LIMIT ?, ?');
        $stm->bindValue(1, $limitFrom, PDO::PARAM_INT);
        $stm->bindValue(2, $perPage, PDO::PARAM_INT);
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_UNIQUE);
        return $data;
    }

    public function fetchLineByGet($var, $totalRowCount)
    {
        $page = array_key_exists('page', $var) ? intval($var['page']) : 1;   //присвоения номера странницы

       // $field= array_key_exists('field', $var) ? strval($var['field']) : 'id';
        $sort = array_key_exists('sort', $var) ? explode(' ',$var['sort']) : array('id', 'ASC');
        $search = array_key_exists("search", $var) ? trim(strval($var["search"])) : '';
        //$fieldAndDir = explode(' ', $sort);
        $field = $sort['0'];
        if (isset($search)) {
            $x = preg_replace("/\s+/", " ", $search);
            $dataka = explode(" ", $x);
            if (count($dataka) >= 2) {
                $varSearch ['0'] = $dataka['0'];
                $varSearch ['1'] = $dataka['1'];

            } else {
                $varSearch = $dataka;
                $varSearch [] = $varSearch['0'];

            }
            foreach ($varSearch as $datum){
                $params[] = "%$datum%";
            }
        }

        $dirs = array("ASC","DESC");
        $key  = array_search($sort['1'],$dirs);
        $dir  = $dirs[$key];

        if($totalRowCount > 50){
            $limitFrom = 0;
        }else {
            $limitFrom = $page * 50 - 50;
        }
        $perPage = $totalRowCount;


        if ($varSearch['0'] !== $varSearch['1']){
            $stm = $this->conn->prepare("SELECT id, firstName, surName, groupNumber, points FROM abiturients WHERE firstName LIKE ? AND surname LIKE ? ORDER BY $field $dir LIMIT $limitFrom, $perPage");
            $stm->execute($params);
            $datas = $stm->fetchAll(PDO::FETCH_UNIQUE);
            $params = array_reverse($params);
            $stm->execute($params);
            $datasu = $stm->fetchAll(PDO::FETCH_UNIQUE);
            $data = array_merge($datas,$datasu);
            return $data;
        }elseif($varSearch['0'] == $varSearch['1']){
            $stm = $this->conn->prepare("SELECT id, firstName, surName, groupNumber, points FROM abiturients WHERE firstName LIKE ? OR surname LIKE ? ORDER BY $field $dir LIMIT $limitFrom, $perPage");
            $stm->execute($params);
            $data = $stm->fetchAll(PDO::FETCH_UNIQUE);
            return $data;
        }elseif(!isset($search)){
            $stm = $this->conn->prepare('SELECT id, firstName, surName, groupNumber, points FROM abiturients LIMIT $limitFrom, $perPage');
            $stm->execute();
            $data = $stm->fetchAll(PDO::FETCH_UNIQUE);
            return $data;
        }
    }

    public function getUsersCountWithSearch($searchWord)
    {
        $in  = str_repeat('?,', count($searchWord) - 1) . '?';
        $sql = "SELECT COUNT(*) FROM abiturients WHERE surName IN ($in)";
        $stm = $this->conn->prepare($sql);
        $stm->execute($searchWord);
        $count=$stm->fetch(PDO::FETCH_NUM);
        return $count;
    }

    public function getTotalUsersCount()
    {
        $sql = "SELECT COUNT(*) FROM abiturients";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $count=$stm->fetch(PDO::FETCH_NUM);
        return $count;
    }

    public function findByIDAndPassword($pdoSet, $value){

        $stm = $this->conn->prepare("SELECT * FROM abiturients WHERE " . $pdoSet);

        $stm->execute($value);

        $row = $stm->fetch(PDO::FETCH_LAZY);
        return $row;
    }
}