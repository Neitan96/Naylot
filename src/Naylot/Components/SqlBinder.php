<?php
/**
 * Created by Nathan Almeida
 * Since: 15/04/2016 11:27
 */

namespace Naylot\Components;


use PDO;

class SqlBinder{

    /**
     * @var ClassCallsStorage
     */
    private $binds;


    public function __construct(){
        $this->binds = new ClassCallsStorage();
    }


    /**
     * @param mixed $value
     * @param int $data_type
     * @return string
     */
    public function bindValue($value, $data_type = PDO::PARAM_STR){
        $parameter = ':value'.($this->binds->countCalls('bindValue') + 1);

        $parameters = array($parameter, $value);
        is_null($data_type) OR $parameters[] = $data_type;

        $this->binds->addCall('bindValue', $parameters);
        return $parameter;
    }

    /**
     * @param mixed $variable
     * @param int $data_type
     * @param int $length
     * @param mixed $driver_options
     * @return string
     */
    public function bindParam(&$variable, $data_type = PDO::PARAM_STR, $length = null, $driver_options = null){
        $parameter = ':param'.($this->binds->countCalls('bindParam') + 1);

        $parameters = array($parameter, &$variable);
        is_null($data_type) OR $parameters[] = $data_type;
        is_null($length) OR $parameters[] = $length;
        is_null($driver_options) OR $parameters[] = $driver_options;

        $this->binds->addCall('bindParam', $parameters);
        return $parameter;
    }

    /**
     * @param mixed $column
     * @param mixed $param
     * @param int $type
     * @param int $maxlen
     * @param mixed $driverdata
     */
    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null){
        $parameters = array($column, &$param);
        is_null($type) OR $parameters[] = $type;
        is_null($maxlen) OR $parameters[] = $maxlen;
        is_null($driverdata) OR $parameters[] = $driverdata;

        $this->binds->addCall('bindColumn', $parameters);
    }

    /**
     * @param \PDOStatement $prepare
     */
    public function bindStatement(&$prepare){
        $this->binds->executeCalls($prepare);
    }

}
