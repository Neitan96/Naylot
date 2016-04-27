<?php
/**
 * Created by Nathan Almeida
 * Since: 18/04/2016 13:38
 */

namespace Naylot\Builder;


trait BForeignkey{

    /** @var array */
    protected $toAdd = array();

    /** @var array */
    protected $toRemove = array();


    /**
     * @param string $constraintName
     * @param string $columnLocal
     * @param string $tableRef
     * @param string $columnRef
     * @param string $onDelete
     * @param string $onUpdate
     * @return $this
     */
    public function addForeignkey($constraintName, $columnLocal, $tableRef, $columnRef,
        $onDelete = null, $onUpdate = null){
        $this->toAdd[] = array(
            'Name' => $constraintName,
            'ColumnLocal' => $columnLocal,
            'TableRef' => $tableRef,
            'ColumnRef' => $columnRef,
            'OnDelete' => $onDelete,
            'OnUpdate' => $onUpdate
        );
        return $this;
    }

    /**
     * @param string $constraintName
     * @return $this
     */
    public function dropForeignkey($constraintName){
        $this->toAdd[] = $constraintName;
        return $this;
    }

}