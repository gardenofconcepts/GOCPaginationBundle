<?php

namespace GOC\PaginationBundle;

use Symfony\Component\DependencyInjection\Container;

class Factory
{
    private $container, $class, $classOdm;

    public function __construct(Container $container, $class, $classOdm)
    {
        $this->container = $container;
        $this->class     = $class;
        $this->classOdm  = $classOdm;
    }

    public function create($query, $items = 50, $page = null)
    {
        if ($page == null) {
            $page = $this->container->get('request')->attributes->get('page')-1;
            if ($page < 0) {
                throw Exception::unknownPageNumber();
            }
        }
        $class = $query instanceof \Doctrine\ODM\MongoDB\Query\Builder ? $this->classOdm : $this->class;

        return new $class($this->container, $query, (int)$items, (int)$page);
    }
}
