<?php
/**
 * Created by Nathan Almeida
 * Since: 23/04/2016 17:33
 */

namespace Naylot\Builder;


use Naylot\Components\SqlComponent;

trait BGroupBy{

    /**
     * @var array
     * [
     *  ['Reference']
     * ]
     */
    protected $refereces = array();


    /**
     * @param array|\Closure|SqlComponent|string $refereces
     * @return $this
     */
    public function groupBy($refereces){
        is_array($refereces) || $refereces = array($refereces);

        foreach($refereces as $referece)
            $this->refereces[] = array('Reference' => $referece);

        return $this;
    }

}