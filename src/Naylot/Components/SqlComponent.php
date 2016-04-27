<?php
/**
 * Created by Nathan Almeida
 * Since: 17/04/2016 01:39
 */

namespace Naylot\Components;


interface SqlComponent{

    /**
     * @param SqlBinder $binds
     * @return null|string
     */
    public function compileSql(&$binds = null);

    /**
     * @return bool
     */
    public function isValid();

}