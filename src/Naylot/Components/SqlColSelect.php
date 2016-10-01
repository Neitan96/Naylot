<?php
/**
 * Created by Nathan Almeida
 * Since: 19/04/2016 12:56
 */

namespace Naylot\Components;


use Naylot\Compilers\SqlCompilerDefault;

class SqlColSelect implements SqlComponent{

    /**
     * @var string|array|SqlComponent
     */
    protected $column;

    /**
     * @var array
     * [
     *   'Mod'
     * ]
     */
    protected $mods = array();


    /**
     * SqlColSelect constructor.
     * @param array|SqlComponent|string $column
     */
    public function __construct($column){
        $this->column = $column;
    }


    /**
     * @param array|string|SqlComponent|SqlColSelect $column
     * @return SqlColSelect
     */
    public static function make($column){
        if($column instanceof SqlColSelect)
            return $column;
        else
            return new SqlColSelect($column);
    }


    public function distinct(){
        $this->mods[0] = 'DISTINCT';
        return $this;
    }

    /**
     * @param SqlBinder $binds
     * @return null|string
     */
    public function compileSql(&$binds = null){
        if(!$this->isValid()) return null;

        $value = SqlCompilerDefault::compileRef($this->column, $binds);
        $value = implode(', ', SqlHelper::toArray($value));

        $mods = $this->mods;
        krsort($mods);

        foreach($mods as $mod)
            $value = $mod.' '.$value;

        return $value;
    }

    /**
     * @return bool
     */
    public function isValid(){
        return !is_null($this->column);
    }

}