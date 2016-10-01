<?php
/**
 * Created by PhpStorm.
 * User: neitan96
 * Date: 01/10/16
 * Time: 13:03
 */

namespace Naylot\Builder;


use Naylot\Components\SqlComponent;

trait BUnion{

    /**
     * @var array
     * [
     *  ['Type', 'Union'],
     *  'Union'
     * ]
     */
    protected $unions = array();


    /**
     * @param SqlComponent|string $query
     * @return $this
     */
    public function union($query){
        $this->unions[] = $query;
        return $this;
    }

    /**
     * @param SqlComponent|string $query
     * @return $this
     */
    public function unionAll($query){
        $this->unions[] = ['Type' => 'ALL', 'Union' => $query];
        return $this;
    }

}