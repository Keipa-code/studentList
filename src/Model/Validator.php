<?php

namespace Src\Model;

class Validator
{

    public function validateDataFromGET($get)
    {
        if (isset($get)) {
            $allowedField = ['id ASC', 'firstName ASC', 'firstName DESC', 'surName ASC', 'surName DESC'];
            //$allowedDir = ['ASC', 'DESC'];
            if (isset($get['page'])) {
                $page = intval($get['page']);
                if (!is_numeric($get['page'])) {
                    $error ['page'] = 'Номер странницы не является целым числом';
                } elseif ($page < 1) {
                    $error ['page'] = 'Номер странницы ниже нуля';
                }
            }

            if (isset($get['sort'])) {
                $field = $get['sort'];
                if (!in_array($field, $allowedField)) {
                    $error['field'] = 'Не соответсвует названию поля сортировки';
                }
            }
            if (isset($get['search'])) {
                $search = trim(strval($get['search'] ?? ''));
                if (mb_strlen($search) > 300) {
                    $error['search'] = "В строку поиска можно ввести не более 300 символов";
                }
            }

            //$search = array_key_exists("search", $get) ? strval($get["search"]) : '';
            if (isset($error)) {
                return $error;
            }
        }
    }

    public function validateCookie($cookie){
        if(!isset($cookie['id']) && !is_numeric($cookie['id'])){
            $error['error'] = 'ID из cookie не найдено в базе данных';
        }

        if(!isset($cookie['passwd'])){
            $error['error'] = 'Пароль не подходит';
        }
        if (isset($error)) {
            return $error;
        }
    }
}