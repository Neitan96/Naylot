<?php
/**
 * Created by Nathan Almeida
 * Since: 19/04/2016 03:09
 */

namespace Naylot\Builder;


use Naylot\Components\SqlComponent;

trait BWhere{

    /** @var array */
    protected $wheres = array();

    /**
     * @param string|SqlComponent $value1
     * @param string $comparator
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function andWhere($value1, $comparator, $value2){
        return $this->where($value1, $comparator, $value2, 'AND');
    }

    /**
     * @param string|SqlComponent $value1
     * @param string $comparator
     * @param string|SqlComponent|mixed $value2
     * @param string $prefix
     * @return $this
     */
    public function where($value1, $comparator, $value2, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'Where',
            'Value1' => $value1,
            'Comparator' => $comparator,
            'Value2' => $value2,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value1
     * @param string $comparator
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function orWhere($value1, $comparator, $value2){
        return $this->where($value1, $comparator, $value2, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function andBetween($value, $value1, $value2){
        return $this->between($value, $value1, $value2, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @param string $prefix
     * @return $this
     */
    public function between($value, $value1, $value2, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'Between',
            'Value' => $value,
            'Value1' => $value1,
            'Value2' => $value2,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function orBetween($value, $value1, $value2){
        return $this->between($value, $value1, $value2, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function andNotBetween($value, $value1, $value2){
        return $this->notBetween($value, $value1, $value2, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @param string $prefix
     * @return $this
     */
    public function notBetween($value, $value1, $value2, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'NotBetween',
            'Value' => $value,
            'Value1' => $value1,
            'Value2' => $value2,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|mixed $value1
     * @param string|SqlComponent|mixed $value2
     * @return $this
     */
    public function orNotBetween($value, $value1, $value2){
        return $this->notBetween($value, $value1, $value2, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @return $this
     */
    public function andLike($value, $like){
        return $this->like($value, $like, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @param string $prefix
     * @return $this
     */
    public function like($value, $like, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'Like',
            'Value' => $value,
            'Like' => $like,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @return $this
     */
    public function orLike($value, $like){
        return $this->like($value, $like, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @return $this
     */
    public function andNotLike($value, $like){
        return $this->notLike($value, $like, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @param string $prefix
     * @return $this
     */

    public function notLike($value, $like, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'NotLike',
            'Value' => $value,
            'Like' => $like,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent $like
     * @return $this
     */
    public function orNotLike($value, $like){
        return $this->notLike($value, $like, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @return $this
     */
    public function andIn($value, $values){
        return $this->in($value, $values, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @param string $prefix
     * @return $this
     */
    public function in($value, $values, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'In',
            'Value' => $value,
            'Values' => $values,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @return $this
     */
    public function orIn($value, $values){
        return $this->in($value, $values, 'OR');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @return $this
     */
    public function andNotIn($value, $values){
        return $this->notIn($value, $values, 'AND');
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @param string $prefix
     * @return $this
     */
    public function notIn($value, $values, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'NotIn',
            'Value' => $value,
            'Values' => $values,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param string|SqlComponent $value
     * @param string|SqlComponent|array $values
     * @return $this
     */
    public function orNotIn($value, $values){
        return $this->notIn($value, $values, 'OR');
    }

    /**
     * @param SqlComponent|string $query
     * @return $this
     */
    public function andWhereQuery($query){
        return $this->whereQuery($query, 'AND');
    }

    /**
     * @param SqlComponent|\Closure|string $query
     * @param string $prefix
     * @return $this
     */
    public function whereQuery($query, $prefix = null){
        $this->wheres[] = array(
            'Type' => 'Sql',
            'Query' => $query,
            'Prefix' => $prefix
        );
        return $this;
    }

    /**
     * @param SqlComponent|string $query
     * @return $this
     */
    public function orWhereQuery($query){
        return $this->whereQuery($query, 'OR');
    }

}