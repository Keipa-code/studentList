<?php
namespace Src\Model;

class PdoSet{
    public function pdoSetter($allowed)
    {

       $set = '';
        foreach ($allowed as $field) {
            $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
        }
        return substr($set, 0, -2);
    }

    public function pdoSetterWithAnd($allowed)
    {

        $set = '';
        foreach ($allowed as $field) {
            $set .= str_replace("`", "``", $field) . "=:$field AND ";
        }
        return substr($set, 0, -5);
    }
}
