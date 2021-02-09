<?php

namespace Src\Model;

class MainPage{

    public $sort=[
        'id ASC' => 'По умолчанию',
        'firstName ASC' => 'По Имени (А-Я)',
        'firstName DESC' => 'По Имени (Я-А)',
        'surName ASC' => 'По Фамилии (А-Я)',
        'surName DESC' => 'По Фамилии (Я-А)'
    ];
    public $searchRequest = '';
    public $head = 'Список абитуриентов';
    public $navButton = 'Регистрация';



    public function __construct($var, $cookie)
    {
        $this->searchRequest = $var['search'] ?? '';
        if (isset($cookie['id'])){
            $this->navButton = 'Редактировать профиль';
        }
    }
}