<?php

namespace GOC\PaginationBundle\Tools\Pagination;

use Doctrine\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;
use Countable;
use IteratorAggregate;

/**
 * Paginator
 *
 * The paginator can handle various complex scenarios with DQL.
 *
 * @author Kevin Saliou <kevin@saliou.name>
 * @license New BSD
 */
class PaginatorOdm implements \Countable, \IteratorAggregate
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var int
     */
    private $count;

    /**
     * Constructor.
     *
     * @param Query|Builder $query A Doctrine ODM query or query builder.
     */
    public function __construct($query)
    {
        if ($query instanceof Builder) {
            $query = $query->getQuery();
        }
        $this->query = $query;
    }

    /**
     * Returns the query
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->count = $this->query->count();

        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $arr = $this->query->getQuery();
        $offset = $arr['skip'];
        $length = $arr['limit'];
        $result = $this->query
            ->execute()
            ->limit($length)
            ->skip($offset)
        ;

        return $result;
    }
}
