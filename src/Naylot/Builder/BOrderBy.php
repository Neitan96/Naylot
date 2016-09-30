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
     */
    protected function orderBy($columns, $order = 'ASC'){
        if(is_array($columns)){
            foreach($columns as $column)
                $this->orderBy($column, $order);
        }else
            $this->columns[] = ['Reference' => $columns, 'Order' => $order];
    }

    /**
     * @param SqlComponent|array|string $columns
     */
    public function orderByAsc($columns){
        $this->orderBy($columns, 'ASC');
    }

    /**
     * @param SqlComponent|array|string $columns
     */
    public function orderByDesc($columns){
        $this->orderBy($columns, 'DESC');
    }

}