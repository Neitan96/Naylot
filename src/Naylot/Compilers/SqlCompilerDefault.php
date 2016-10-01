<?php
/**
 * Created by Nathan Almeida
 * Since: 19/04/2016 02:36
 */

namespace Naylot\Compilers;


use Naylot\Components\SqlBinder;
use Naylot\Components\SqlComponent;

class SqlCompilerDefault implements SqlCompiler{


    /**
     * @param \Closure|SqlComponent|array|string $component
     * @param SqlBinder $binds
     * @return string|array
     */
    public static function compileComponent($component, &$binds = null){
        while($component instanceof \Closure || $component instanceof SqlComponent){

            if($component instanceof \Closure)
                $component = $component($binds);

            if($component instanceof SqlComponent)
                $component = $component->compileSql($binds);

        }
        return $component;
    }

    /**
     * @param \Closure|SqlComponent|array|string $value
     * @param SqlBinder $binds
     * @return string|array
     */
    public static function compileRef($value, &$binds = null){
        if(is_array($value)){
            foreach($value as $key => $v)
                $value[$key] = static::compileRef($v);
            return implode(', ', $value);
        }

        if($value instanceof \Closure || $value instanceof SqlComponent)
            return static::compileComponent($value, $binds);

        if(!is_null($value)){
            $value = str_replace('`', '', $value);
            $value = str_replace('.', '`.`', $value);
            return "`{$value}`";
        }

        return 'NULL';
    }

    /**
     * @param \Closure|SqlComponent|array|mixed $value
     * @param SqlBinder $binds
     * @param int $data_type
     * @return string
     */
    public static function compileValue($value, &$binds = null, $data_type = \PDO::PARAM_STR){
        if(is_array($value)){
            foreach($value as $key => $v)
                $value[$key] = static::compileValue($v, $data_type);
            return implode(', ', $value);
        }

        if($value instanceof \Closure)
            return $value($binds, $data_type);

        if($value instanceof SqlComponent)
            return $value->compileSql($binds);

        return $binds->bindValue($value, $data_type);
    }


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
        $foreignkeySQL = 'CONSTRAINT '.$this->compileRef($foreignkey['Name']);
        $foreignkeySQL .= ' FOREIGN KEY ('.$this->compileRef($foreignkey['ColumnLocal']).')';
        $foreignkeySQL .= ' REFERENCES '.$this->compileRef($foreignkey['TableRef']);
        $foreignkeySQL .= '('.$this->compileRef($foreignkey['ColumnRef']).')';

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
        return 'DROP CONSTRAINT '.$this->compileRef($name);
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

    protected function getStartWhere($where, $compilePrefix = true){
        if($compilePrefix && key_exists('Prefix', $where))
            return $where['Prefix'].' ';
        else
            return '';
    }

    public function whereWhere(array $where, $compilePrefix = true){
        $sql = $this->getStartWhere($where, $compilePrefix);
        $sql .= $this->compileRef($where['Value1']).' ';
        $sql .= $where['Comparator'].' ';
        $sql .= $this->compileValue($where['Value2']);
        return $sql;
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
     * @param array $join
     * ['Type', 'LocalRef', 'TableRef', 'ColumnRef']
     * @param string $tableName
     * @return string
     */
    public function join(array $join, $tableName = null){
        $tableRef = $this->compileMyRef($join['TableRef']);
        $joinSql = $join['Type'].' JOIN '.$tableRef;
        if(isset($join['LocalRef']) && isset($join['LocalRef'])){
            $localRef = $this->compileMyRef($join['LocalRef']);
            $columnRef = $this->compileMyRef($join['ColumnRef']);
            strpos($localRef, '.') === false && $this->compileMyRef($tableName).$localRef;
            $joinSql .= $localRef.'='.$tableRef.'.'.$columnRef;
        }
        return $joinSql;
    }

    /**
     * @param array $joins
     * [
     *  ['Type', 'LocalRef', 'TableRef', 'ColumnRef']
     * ]
     * @param string $tableName
     * @return string
     */
    public function joins(array $joins, $tableName = null){
        foreach($joins as $key => $join)
            $joins[$key] = $this->join($join, $tableName);
        return implode(' ', $joins);
    }

    /**
     * @param array|string $refereces
     * [
     *  ['Reference', ...]
     * ]
     * @return string
     */
    public function groupBy(array $refereces){
        foreach($refereces as $key => $referece){
            is_array($referece) || $referece = array('Reference' => $referece);
            $referece['Reference'] = $this->compileRef($referece['Reference']);
            $refereces[$key] = $referece;
        }
        return 'GROUP BY '.implode(', ', $refereces);
    }

    /**
     * @param array|string $refereces
     * [
     *  ['Reference', 'Order']
     * ]
     * @return string
     */
    public function orderBy(array $refereces){
        foreach($refereces as $key => $referece){
            is_array($referece) || array($referece);
            $referece[$key] = $this->compileRef($referece['Reference']) . ' ' . $referece['Order'];
        }
        return 'ORDER BY ' . implode(', ', $refereces);
    }

    /**
     * @param array $limit
     * ['Take', 'Skip']
     * @return string
     */
    public function limit(array $limit){
        $take = $limit['Take'];
        $skip = $limit['Skip'];
        if(!is_null($take)) $take = 'LIMIT '.$take;
        if(!is_null($skip)) $skip = 'OFFSET '.$skip;
        return $take.(!is_null($take) && !is_null($take) ? ' ' : null).$skip;
    }

    /**
     * @param array|string $union
     * ['Type', 'Union']
     * 'Union'
     * @return string
     */
    public function union($union){
        if(is_array($union)){
            return 'UNION '.(isset($union['Type']) ? $union['Type'].' ' : '')
            .$this->compileMycomponent($union['Union']);
        }else{
            return 'UNION '.$this->compileMycomponent($union);
        }
    }

    /**
     * @param array|string $unions
     * [
     *  ['Type', 'Union']
     *  'Union'
     * ]
     * @return string
     */
    public function unions($unions){
        foreach($unions as $key => $union)
            $unions[$key] = $this->union($union);
        return implode(' ', $unions);
    }

    /**
     * @param array $options
     * [
     *  ['Key', 'Value']
     * ]
     * @return string
     */
    public function tableOptions($options){
        $tableOptionsSql = null;
        foreach($options as $option){
            is_null($tableOptionsSql) || $tableOptionsSql .= ' ';
            $methodName = 'tableOption'.$option['Key'];
            if(method_exists($this, $methodName)){
                $return = call_user_func(array($this, $methodName), $option);
                if(!is_null($return)) $tableOptionsSql .= ' '.$return;
            }else{
                $tableOptionsSql .= ' '.$option['Key'].(
                    key_exists('Value', $option) && !is_null($option['Value']) ?
                        ' = '.$option['Value'] : null
                    );
            }
        }
        return $tableOptionsSql;
    }

    public function tableOptionAutoincrement(array $option){
        return 'AUTO_INCREMENT = '.(int)$option['Value'];
    }

    public function tableOptionEngine(array $option){
        return 'ENGINE = '.$option['Value'];
    }

    public function tableOptionCharset(array $option){
        return 'CHARSET = '.$option['Value'];
    }

    public function tableOptionCollate(array $option){
        return 'COLLATE = '.$option['Value'];
    }

    /**
     * @param \Closure|SqlComponent|array|string $component
     * @return string|array
     */
    protected function compileMycomponent($component){
        return static::compileComponent($component, $this->binds);
    }

    /**
     * @param \Closure|SqlComponent|array|string $value
     * @return string|array
     */
    protected function compileMyRef($value){
        return static::compileRef($value, $this->binds);
    }

    /**
     * @param \Closure|SqlComponent|array|mixed $value
     * @param int $data_type
     * @return string
     */
    protected function compileMyValue($value, $data_type = \PDO::PARAM_STR){
        return static::compileValue($value, $this->binds, $data_type);
    }

}