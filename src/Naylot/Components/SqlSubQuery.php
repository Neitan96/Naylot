<?php
/**
 * Created by Nathan Almeida
 * Since: 14/04/2016 03:09
 */

namespace Naylot\Components;


use Naylot\Components;

class SqlSubQuery implements SqlComponent{

    /**
     * @var string
     */
    public $query;


    /**
     * SubQuery constructor.
     * @param string $query
     */
    public function __construct($query){
        $this->query = $query;
    }


    /**
     * @param SqlBinder $binds
     * @return string|null
     */
    public function compileSql(&$binds = null){
        if(!$this->isValid()) return null;
        return '('.$this->query.')';
    }

    /**
     * @return bool
     */
    public function isValid(){
        return !is_null($this->query);
    }

}