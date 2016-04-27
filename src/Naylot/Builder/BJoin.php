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
    public function innerJoin($tableRef, $localRef, $columnRef){
        $this->joins[] = array(
            'INNER', $tableRef, $localRef, $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function leftJoin($tableRef, $localRef, $columnRef){
        $this->joins[] = array(
            'LEFT', $tableRef, $localRef, $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function rightJoin($tableRef, $localRef, $columnRef){
        $this->joins[] = array(
            'RIGHT', $tableRef, $localRef, $columnRef
        );
        return $this;
    }

    /**
     * @param string $tableRef
     * @param string $localRef
     * @param string $columnRef
     * @return $this
     */
    public function fullJoin($tableRef, $localRef, $columnRef){
        $this->joins[] = array(
            'FULL', $tableRef, $localRef, $columnRef
        );
        return $this;
    }

}