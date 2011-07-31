<?php

namespace GOC\PaginationBundle;

use Symfony\Component\DependencyInjection\Container;

class Factory
{
    private $container;
    private $class;

    public function __construct(Container $container, $class)
    {
        $this->container = $container;
        $this->class = $class;
    }
    public function create($query, $items = 50, $page = null)
    {
        if ($page == null) {
            $page = $this->container->get('request')->attributes->get('page')-1;
            if ($page < 0) {
                throw Exception::unknownPageNumber();
            }
        }

        $class = $this->class;

        return new $class($this->container, $query, (int)$items, (int)$page);
    }
}
