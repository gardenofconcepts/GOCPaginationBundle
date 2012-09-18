<?php

namespace GOC\PaginationBundle;

use GOC\PaginationBundle\Paginator as Paginator;
use GOC\PaginationBundle\Tools\Pagination\PaginatorOdm as DoctrinePaginatorOdm;

class PaginatorOdm extends Paginator implements Pagination
{
    protected function createQuery($query)
    {
        $query
            ->skip($this->getPage() * $this->getItemsPerPage())
            ->limit($this->getItemsPerPage())
        ;

        return new DoctrinePaginatorOdm($query);
    }
}
