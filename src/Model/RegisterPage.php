<?php
namespace Src\Model;

use Src\Model\Tdgw;
use Src\Model\SearchValidator;

Class RegisterPage
{
    public $data = [
        'firstName' => '',
        'surName' => '',
        'groupNumber' => '',
        'email' => '',
        'points' => '',
        'birthYear' => ''
    ];
    public $radio = [
        'genderMan' => 'Мужчина',
        'genderWoman' => 'Женщина',
        'locationCity' => 'Местный',
        'locationNonresident' => 'Иногородний'
    ];

    public $cookie = ['id' => '', 'passwd' => ''];

    public function putData($var)
    {
        //var_dump($var);
        foreach ($this->radio as $key => $item) {
            if ($this->radio[$key] == $var['gender'] || $this->radio[$key] == $var['location']) {
                $this->radio[$key] = 'checked';
            }else{
                $this->radio[$key] = '';
            }
        }

        foreach ($this->data as $key => $datum) {
            $this->data[$key] = $var[$key];

        }
        //var_dump($this->data);
    }

    public function fillCookie($cook){
        if (isset($cook['id']) && isset($cook['passwd'])){
            $this->cookie = ['id' => htmlspecialchars($cook['id']), 'passwd' => htmlspecialchars($cook['passwd'])];
        }
    }
}
