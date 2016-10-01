<?php
/**
 * Created by PhpStorm.
 * User: neitan96
 * Date: 01/10/16
 * Time: 13:58
 */

namespace Naylot\Helpers;


use Naylot\Components\SqlColSelect;
use Naylot\Components\SqlFunc;
use Naylot\Components\SqlLiteral;
use Naylot\Components\SqlSubQuery;

final class SQL{

    public static function literal($value){
        return SqlLiteral::make($value);
    }

    public static function funcColumn($column){
        return SqlFunc::make($column, true);
    }

    public static function funcValue($value, $data_type = null){
        return SqlFunc::make($value, false, $data_type);
    }

    public static function columnSelect($column){
        return SqlColSelect::make($column);
    }

    public static function subQuery($query){
        return SqlSubQuery::make($query);
    }

    private function __construct(){
    }

}