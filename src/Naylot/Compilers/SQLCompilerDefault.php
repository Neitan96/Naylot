<?php
/**
 * Created by Nathan Almeida
 * Since: 19/04/2016 02:36
 */

namespace Naylot\Compilers;


use Naylot\Components\SqlBinder;
use Naylot\Components\SqlComponent;
use Naylot\Components\SqlHelper;

class SQLCompilerDefault implements SQLCompiler{

    /** @var SqlBinder */
    protected $binds;


    /**
     * SQLCompilerDefault constructor.
     * @param SqlBinder $binds
     */
    public function __construct(SqlBinder $binds = null){
        $this->binds = is_null($binds) ? new SqlBinder() : $binds;
    }

    /**
     * @param array $foreignkeys
     * @return string
     */
    public function foreignkeysToAdd(array $foreignkeys){
        foreach($foreignkeys as $key => $foreignkey)
            $foreignkeys[$key] = $this->foreignkeyToAdd($foreignkey);
        return implode(', ', $foreignkeys);
    }

    /**
     * @param array $foreignkey
     * @return string
     */
    public function foreignkeyToAdd(array $foreignkey){
        $foreignkeySQL = 'CONSTRAINT '.SqlHelper::processRef($foreignkey['Name']);
        $foreignkeySQL .= ' FOREIGN KEY ('.SqlHelper::processRef($foreignkey['ColumnLocal']).')';
        $foreignkeySQL .= ' REFERENCES '.SqlHelper::processRef($foreignkey['TableRef']);
        $foreignkeySQL .= '('.SqlHelper::processRef($foreignkey['ColumnRef']).')';

        if(key_exists('OnDelete', $foreignkey))
            $foreignkeySQL .= ' ON DELETE '.$foreignkey['OnDelete'];
        if(key_exists('OnUpdate', $foreignkey))
            $foreignkeySQL .= ' ON UPDATE '.$foreignkey['OnUpdate'];

        return $foreignkeySQL;
    }

    /**
     * @param array $names
     * @return string
     */
    public function foreignkeysToDrop(array $names){
        foreach($names as $key => $name)
            $names[$key] = $this->foreignkeyToDrop($name);
        return implode(', ', $names);
    }

    /**
     * @param string $name
     * @return string
     */
    public function foreignkeyToDrop($name){
        return 'DROP CONSTRAINT '.SqlHelper::processRef($name);
    }

    /**
     * @param array $wheres
     * @return string
     */
    public function wheres(array $wheres){
        $compilePrefix = false;
        foreach($wheres as $key => $where){
            $wheres[$key] = $this->where($where, $compilePrefix);
            $compilePrefix = true;
        }
        return implode(', ', $wheres);
    }

    /**
     * @param array $where
     * @param bool $compilePrefix
     * @return string
     */
    public function where(array $where, $compilePrefix = true){
        if(key_exists('Type', $where) && method_exists($this, 'where'.$where['Type']))
            return call_user_func(array($this, 'where'.$where['Type']), $where, $compilePrefix);
        else
            return null;
    }

    public function whereWhere(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value1']).' ';
        $sql .= $where['Comparator'].' ';
        $sql .= $this->compileValue($where['Value2']);
        return $sql;
    }

    protected function getStartWhere($where, $compilePrefix = true){
        if($compilePrefix && key_exists('Prefix', $where))
            return $where['Prefix'].' ';
        else
            return '';
    }

    /**
     * @param \Closure|SqlComponent|array|string $value
     * @return string|array
     */
    protected function compileRef($value){
        if(is_array($value)){
            foreach($value as $key => $v)
                $value[$key] = $this->compileRef($v);
            return implode(', ', $value);
        }

        if($value instanceof \Closure)
            return $value($this->binds);

        if($value instanceof SqlComponent)
            return $value->compileSql($this->binds);

        if(!is_null($value)){
            $value = str_replace('`', '', $value);
            $value = str_replace('.', '`.`', $value);
            return "`{$value}`";
        }

        return 'NULL';
    }

    /**
     * @param \Closure|SqlComponent|array|mixed $value
     * @param int $data_type
     * @return string
     */
    protected function compileValue($value, $data_type = \PDO::PARAM_STR){
        if(is_array($value)){
            foreach($value as $key => $v)
                $value[$key] = $this->compileValue($v, $data_type);
            return implode(', ', $value);
        }

        if($value instanceof \Closure)
            return $value($this->binds, $data_type);

        if($value instanceof SqlComponent)
            return $value->compileSql($this->binds);

        return $this->binds->bindValue($value, $data_type);
    }

    public function whereBetween(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' BETWEEN ';
        $sql .= $this->compileValue($where['Value1']);
        $sql .= ' AND ';
        $sql .= $this->compileValue($where['Value2']);
        return $sql;
    }

    public function whereNotBetween(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' NOT BETWEEN ';
        $sql .= $this->compileValue($where['Value1']);
        $sql .= ' AND ';
        $sql .= $this->compileValue($where['Value2']);
        return $sql;
    }

    public function whereLike(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' LIKE ';
        $sql .= $this->compileValue($where['Like']);
        return $sql;
    }

    public function whereNotLike(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' NOT LIKE ';
        $sql .= $this->compileValue($where['Like']);
        return $sql;
    }

    public function whereIn(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' IN ';
        $sql .= '('.$this->compileValue($where['Values']).')';
        return $sql;
    }

    public function whereNotIn(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value']);
        $sql .= ' NOT IN ';
        $sql .= '('.$this->compileValue($where['Values']).')';
        return $sql;
    }

    public function whereSql(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix).'(';

        if($where['Query'] instanceof SqlComponent)
            $sql .= $where['Query']->compileSql($this->binds);
        else
            $sql .= $where['Query'];

        return $sql.')';
    }

    /**
     * @param array|string $refereces
     * [
     *  ['Reference', ...]
     * ]
     * @return string
     */
    public function groupBy(array $refereces){
        // TODO: Implement groupBy() method.
    }

    /**
     * @param array $limit
     * ['Take', 'Skip']
     * @return string
     */
    public function limit(array $limit){
        // TODO: Implement limit() method.
    }

    /**
     * @param array $options
     * [
     *  ['Key' => 'Value']
     * ]
     * @return string
     */
    public function tableOptions($options){
        // TODO: Implement tableOptions() method.
    }
}