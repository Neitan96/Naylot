<?php
/**
 * Created by Nathan Almeida
 * Since: 15/04/2016 14:16
 */

namespace Naylot\Helpers;


class ClassCallsStorage{

    public $calls = array();

    /**
     * @param $index
     * @return int
     */
    public function countCalls($index){
        if(array_key_exists($index, $this->calls))
            return count($this->calls[$index]);
        else
            return 0;
    }

    /**
     * @param $index
     * @param $call
     */
    public function addCall($index, &$call){
        if(!array_key_exists($index, $this->calls))
            $this->calls[$index] = array();
        $this->calls[$index][] = &$call;
    }

    /**
     * @param mixed $object
     */
    public function executeCalls(&$object){
        if(count($this->calls) > 0){
            foreach($this->calls as $funcName => $calls){
                foreach($calls as $call)
                    call_user_func_array(array(&$object, $funcName), $call);
            }
        }
    }

}