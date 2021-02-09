<?php
namespace Src\Model;

use Src\Model\PasswordGenerator;

class Student
{
    public $student=array();

    public function fillStudentDataFromPost($data)
    {
        foreach ($data as $key => $datium){
            $this->student[$key] = $datium;
        }
        $passGen = new PasswordGenerator;
        $this->student['passwd'] = $passGen->generatePassword(32);
        unset($this->student['token']);
        unset($this->student['submittype']);
        //var_dump($this->student);
    }

}