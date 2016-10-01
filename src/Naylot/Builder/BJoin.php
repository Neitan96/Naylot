<?php
/**
 * Created by Nathan Almeida
 * Since: 23/04/2016 18:19
 */

namespace Naylot\Builder;


trait BJoin{

    /** @var array */
    protected $joins = array();


    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function innerJoin($localRef, $tableRef, $columnRef){
        $this->joins[] = array(
            'Type' => 'INNER',
            'LocalRef' => $localRef,
            'TableRef' => $tableRef,
            'ColumnRef' => $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function leftJoin($localRef, $tableRef, $columnRef){
        $this->joins[] = array(
            'Type' => 'LEFT',
            'LocalRef' => $localRef,
            'TableRef' => $tableRef,
            'ColumnRef' => $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function rightJoin($localRef, $tableRef, $columnRef){
        $this->joins[] = array(
            'Type' => 'RIGHT',
            'LocalRef' => $localRef,
            'TableRef' => $tableRef,
            'ColumnRef' => $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function fullJoin($localRef, $tableRef, $columnRef){
        $this->joins[] = array(
            'Type' => 'FULL',
            'LocalRef' => $localRef,
            'TableRef' => $tableRef,
            'ColumnRef' => $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @return $this
     */
    public function crossJoin($tableRef){
        $this->joins[] = array(
            'Type' => 'CROSS',
            'TableRef' => $tableRef,
        );
        return $this;
    }

}