<?php
/**
 * Created by PhpStorm.
 * User: neitan96
 * Date: 29/09/16
 * Time: 20:36
 */

namespace Naylot\Builder;


use Naylot\Components\SqlComponent;

trait BOrderBy{

    /**
     * @var array
     * [
     *  ['Column', 'Order']
     * ]
     */
    protected $columns = array();


    /**
     * @param SqlComponent|array|string $columns
     * @param string $order
     * @return $this
     */
    protected function orderBy($columns, $order = 'ASC'){
        if(is_array($columns)){
            foreach($columns as $column)
                $this->orderBy($column, $order);
        }else
            $this->columns[] = ['Reference' => $columns, 'Order' => $order];

        return $this;
    }

    /**
     * @param SqlComponent|array|string $columns
     * @return $this
     */
    public function orderByAsc($columns){
        return $this->orderBy($columns, 'ASC');
    }

    /**
     * @param SqlComponent|array|string $columns
     * @return $this
     */
    public function orderByDesc($columns){
        return $this->orderBy($columns, 'DESC');
    }

}