<?php
/**
 * Created by Nathan Almeida
 * Since: 23/04/2016 17:49
 */

namespace Naylot\Builder;


trait BLimit{

    /**
     * @var array
     */
    protected $limit = array();

    /**
     * @param int $take
     * @param int $skip
     * @return $this
     */
    public function limit($take, $skip = null){
        return $this->take($take)->skip($skip);
    }

    /**
     * @param int $skip
     * @return $this
     */
    public function skip($skip){
        $this->limit['Skip'] = (int)$skip;
        return $this;
    }

    /**
     * @param int $take
     * @return $this
     */
    public function take($take){
        $this->limit['Take'] = (int)$take;
        return $this;
    }

}