<?php
/**
 * User: neitan96
 * Date: 19/03/16
 * Time: 20:49
 */

namespace Naylot\Components;


class SqlHelper{

    public static function array_element($index, $array, $default = null){
        return is_array($array) && isset($array[$index]) ? $array[$index] : $default;
    }

    public static function toArray($value){
        if(is_array($value)) return $value;
        else if($value === null) return array();
        else return array($value);
    }

}