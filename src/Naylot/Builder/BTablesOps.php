<?php
/**
 * Created by Nathan Almeida
 * Since: 23/04/2016 18:05
 */

namespace Naylot\Builder;


trait BTablesOps{

    /** @var array */
    protected $options = array();

    /**
     * @param int $id
     * @return $this
     */
    public function autoIncrement($id){
        return $this->tableOption('AUTOINCREMENT', $id);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function tableOption($key, $value){
        $this->options[] = array($key, $value);
        return $this;
    }

    /**
     * @param string $engine
     * @return $this
     */
    public function engine($engine){
        return $this->tableOption('ENGINE', $engine);
    }

    /**
     * @param string $charset
     * @return $this
     */
    public function charset($charset){
        return $this->tableOption('CHARSET', $charset);
    }

    /**
     * @param string $collate
     * @return $this
     */
    public function collate($collate){
        return $this->tableOption('COLLATE', $collate);
    }

}