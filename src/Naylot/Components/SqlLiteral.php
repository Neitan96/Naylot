<?php
/**
 * Created by Nathan Almeida
 * Since: 23/04/2016 17:36
 */

namespace Naylot\Components;


class SqlLiteral implements SqlComponent{

    /** @var mixed */
    protected $value;


    /**
     * SqlLiteral constructor.
     * @param mixed $value
     */
    public function __construct($value){
        $this->value = $value;
    }


    /**
     * @param mixed $value
     * @return SqlLiteral
     */
    public static function make($value){
        return new SqlLiteral($value);
    }


    /**
     * @param SqlBinder $binds
     * @return null|string
     */
    public function compileSql(&$binds = null){
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isValid(){
        return true;
    }

}