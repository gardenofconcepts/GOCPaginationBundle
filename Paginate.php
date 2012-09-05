<?php

namespace GOC\PaginationBundle;

use Doctrine\ORM\Query;

class Paginate extends \DoctrineExtensions\Paginate\Paginate
{
    /**
     * Given the Query it returns a new query that is a paginatable query using a modified subselect.
     *
     * @param Query $query
     * @return Query
     */
    static public function getPaginateQuery(Query $query, $offset, $itemCountPerPage)
    {
        $ids = array_map('current', self::createLimitSubQuery($query, $offset, $itemCountPerPage)->getScalarResult());

        if (count($ids) === 0) {
            $ids = array(0);
        }

        return self::createWhereInQuery($query, $ids);
    }
}
