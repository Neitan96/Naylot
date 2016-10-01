<?php
/**
 * Created by Nathan Almeida
 * Since: 17/04/2016 02:02
 */

namespace Naylot\Components;


use Naylot\Compilers\SqlCompilerDefault;

class SqlFunc implements SqlComponent{

    /** @var mixed */
    protected $value;

    /** @var bool */
    protected $isColumn = true;

    /** @var int */
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
     * @param bool $isColumn
     * @param int $data_type
     */
    public function __construct($value, $isColumn = true, $data_type = null){
        $this->value = $value;
        $this->isColumn = $isColumn;
        $this->data_type = $data_type;
    }


    /**
     * @param mixed $value
     * @param bool $isColumn
     * @param int $data_type
     * @return SqlFunc
     */
    public static function make($value, $isColumn = true, $data_type = null){
        if($value instanceof SqlFunc)
            return $value;
        else
            return new SqlFunc($value, $isColumn, $data_type);
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
        $this->addFunction('FORMAT(', SqlCompilerDefault::compileValue($format).')');
        return $this;
    }

    /**
     * @param SqlBinder $binds
     * @return null|string
     */
    public function compileSql(&$binds = null){
        if(!$this->isValid()) return null;

        $value = SqlCompilerDefault::compileComponent($this->value, $binds);

        if(!$this->isColumn)
            if(!is_null($this->data_type))
                $value = $binds->bindValue($value, $this->data_type);
            else
                $value = $binds->bindValue($value);
        else
            $value = SqlCompilerDefault::compileRef($value);

        foreach($this->functions as $function)
            $value = $function[0].$value.SqlHelper::array_element(1, $function);

        return $value;
    }

    /**
     * @return bool
     */
    public function isValid(){
        return !is_null($this->value);
    }

}