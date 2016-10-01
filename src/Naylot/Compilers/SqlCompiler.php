<?php
/**
 * Created by Nathan Almeida
 * Since: 19/04/2016 02:34
 */

namespace Naylot\Compilers;


interface SqlCompiler{

    /**
     * @param array $foreignkey
     * [
     *  'Name',
     *  'ColumnLocal',
     *  'TableRef',
     *  'ColumnRef',
     *  'OnDelete',
     *  'OnUpdate',
     * ]
     * @return string
     */
    public function foreignkeyToAdd(array $foreignkey);

    /**
     * @param array $foreignkeys
     * @return string
     */
    public function foreignkeysToAdd(array $foreignkeys);

    /**
     * @param string $name
     * @return string
     */
    public function foreignkeyToDrop($name);

    /**
     * @param array $names
     * @return string
     */
    public function foreignkeysToDrop(array $names);

    /**
     * @param array $where
     * [
     *  'Type', ...
     * ]
     * @return string
     */
    public function where(array $where);

    /**
     * @param array $wheres
     * [
     *    ['Where', 'Value1', 'Comparator', 'Value2', 'Prefix'],
     *    ['Between', 'Value', 'Value1', 'Value2', 'Prefix'],
     *    ['NotBetween', 'Value', 'Value1', 'Value2', 'Prefix'],
     *    ['Like', 'Value', 'Like', 'Prefix'],
     *    ['NotLike', 'Value', 'Like', 'Prefix'],
     *    ['In', 'Value', 'Values', 'Prefix'],
     *    ['NotIn', 'Value', 'Values', 'Prefix'],
     *    ['Sql', 'Query', 'Prefix'],
     * ]
     * @return string
     */
    public function wheres(array $wheres);

    /**
     * @param array $join
     * ['Type', 'LocalRef', 'TableRef', 'ColumnRef']
     * @param string $tableName
     * @return string
     */
    public function join(array $join, $tableName = null);

    /**
     * @param array $joins
     * [
     *  ['Type', 'LocalRef', 'TableRef', 'ColumnRef']
     * ]
     * @param string $tableName
     * @return string
     */
    public function joins(array $joins, $tableName = null);

    /**
     * @param array|string $refereces
     * [
     *  ['Reference', ...]
     * ]
     * @return string
     */
    public function groupBy(array $refereces);

    /**
     * @param array|string $refereces
     * [
     *  ['Reference', 'Order']
     * ]
     * @return string
     */
    public function orderBy(array $refereces);

    /**
     * @param array $limit
     * ['Take', 'Skip']
     * @return string
     */
    public function limit(array $limit);

    /**
     * @param array|string $union
     * ['Type', 'Union']
     * 'Union'
     * @return string
     */
    public function union($union);

    /**
     * @param array|string $unions
     * [
     *  ['Type', 'Union']
     *  'Union'
     * ]
     * @return string
     */
    public function unions($unions);

    /**
     * @param array $options
     * [
     *  ['Key' => 'Value']
     * ]
     * @return string
     */
    public function tableOptions($options);

}