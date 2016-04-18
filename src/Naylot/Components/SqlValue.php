<?php
/**
 * Created by Nathan Almeida
 * Since: 17/04/2016 02:02
 */

namespace Naylot\Components;


class SqlValue{

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var int
     */
    protected $data_type = null;

    /**
     * @var array
     * [
     *   ['Prefix', 'Sufix']
     * ]
     */
    protected $functions = null;


    /**
     * SqlFun constructor.
     * @param mixed $value
     * @param int $data_type
     */
    public function __construct($value, $data_type = null){
        $this->value = $value;
        $this->data_type = $data_type;
    }


    /**
     * @param mixed $value
     * @param int $data_type
     * @return SqlValue|SqlComponent
     */
    public static function make($value, $data_type = null){
        if(!$value instanceof SqlComponent)
            return new SqlValue($value, $data_type);
        else
            return $value;
    }


    public function avg(){
        $this->addFunction('AVG(');
        return $this;
    }

    protected function addFunction($prefix, $sufix = ')'){
        if(is_null($this->functions)) $this->functions = array();
        $this->functions[] = array($prefix, $sufix);
    }

    public function count(){
        $this->addFunction('COUNT(');
        return $this;
    }

    public function first(){
        $this->addFunction('FIRST(');
        return $this;
    }

    public function last(){
        $this->addFunction('LAST(');
        return $this;
    }

    public function max(){
        $this->addFunction('MAX(');
        return $this;
    }

    public function sum(){
        $this->addFunction('SUM(');
        return $this;
    }

    public function ucase(){
        $this->addFunction('UCASE(');
        return $this;
    }

    public function lcase(){
        $this->addFunction('LCASE(');
        return $this;
    }

    public function mid($start = 1, $end = null){
        $args = ((int)$start).(is_null($end) ? '' : ','.((int)$end));
        $this->addFunction('MID(', $args.')');
        return $this;
    }

    public function len(){
        $this->addFunction('LEN(');
        return $this;
    }

    public function round($decimals){
        $this->addFunction('ROUND(', ((int)$decimals).')');
        return $this;
    }

    public function now(){
        $this->addFunction('NOW(');
        return $this;
    }

    public function format($format){
        $this->addFunction('FORMAT(', SqlHelper::processValue($format).')');
        return $this;
    }

    /**
     * @param SqlBinder $binds
     * @return null|string
     */
    public function compileSql(&$binds = null){
        if(!$this->isValid()) return null;

        $value = $this->value;
        if($value instanceof \Closure){
            $sqlFunctions = $value($binds);
        }elseif($value instanceof SqlComponent){
            $sqlFunctions = $value->compileSql($binds);
        }else{
            if(!is_null($this->data_type))
                $sqlFunctions = $binds->bindValue($value, $this->data_type);
            else
                $sqlFunctions = $binds->bindValue($value);
        }

        foreach($this->functions as $function)
            $sqlFunctions = $function[0].$sqlFunctions.SqlHelper::array_element(1, $function);

        return $sqlFunctions;
    }

    /**
     * @return bool
     */
    public function isValid(){
        return !is_null($this->value);
    }

}