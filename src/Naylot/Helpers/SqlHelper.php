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

    public static function processRef($column){
        if(is_array($column)){
            $newArray = array();
            foreach($column as $key => $value)
                $newArray[$key] = static::processRef($value);

            return $newArray;
        }else{
            if(!is_null($column)){
                $column = str_replace('`', '', $column);
                $column = str_replace('.', '`.`', $column);
                $column = "`{$column}`";
                return $column;

            }else return 'NULL';
        }
    }

    public static function processValue($value){
        if(is_array($value)){
            $newArray = array();
            foreach($value as $key => $valueArray)
                $newArray[$key] = static::processValue($valueArray);

            return $newArray;
        }else{
            if(!is_null($value)){

                if(is_bool($value))
                    return $value ? '1' : '0';

                if(!is_numeric($value))
                    return "'".str_replace("'", "''", $value)."'";

                return $value;

            }else return 'NULL';
        }
    }

}