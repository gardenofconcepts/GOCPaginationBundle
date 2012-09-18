<?php

namespace GOC\PaginationBundle;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator implements Pagination
{
    private $container;
    private $query;
    private $items = 0;
    private $page = 1;
    private $pages = 1;
    private $itemsPerPage = 50;
    private $url;

    public function __construct($container, $query, $itemsPerPage, $page)
    {
        $this->setContainer($container);
        $this->setPage($page);
        $this->setItemsPerPage($itemsPerPage);
        $this->setItems( $this->countItems($query) );
        $this->setQuery( $this->createQuery($query));
    }

    protected function countItems($query)
    {
        return $this->createQuery($query)->count();
    }

    protected function createQuery($query)
    {
        $query
            ->setFirstResult($this->getPage() * $this->getItemsPerPage())
            ->setMaxResults($this->getItemsPerPage())
        ;
    
        return new DoctrinePaginator($query);
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getResult()
    {
        return $this->getQuery();
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPages()
    {
        return ceil($this->items / $this->getItemsPerPage());
    }

    public function setItemsPerPage($items)
    {
        $this->itemsPerPage = $items;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        if ($this->url !== null) {
            return $this->url;
        }

        $router = $this->container->get('router');
        $route  = $this->container->get('request')->attributes->get('_route');
        $params = $this->container->get('request')->attributes->all();
        $params = $params + $this->container->get('request')->query->all();
        $params['page'] = '000';

        foreach ($params as $key => $value) {
            if ($key{0} == '_') {
                unset($params[$key]);
            }
        }

        return $router->generate($route, $params);
    }

    public function render(array $params = array())
    {
        $page = $this->getPage()+1;
        $pages = ceil($this->getPages());
        $result = array();

        if ($page > 1 && $pages > 1) {
            $result[] = '<li class="prev"><a href="' . $this->generateUrl($page-1) . '" class="prev">«</a></li>';
        }

        if ($page > 4) {
            $result[] = '<li class="first"><a href="' . $this->generateUrl(1) . '" class="first">1</a></li>';
            if ($page-1 > 4) {
                $result[] = '<li class="spacer spacer-prev">...</li>';
            }
        }

        for ($i = $page-3; $i < $page+4; $i++) {
            if ($i > 0 && $i <= $pages) {
                $class = ($i == $page) ? ' class="active"' : '';
                $result[] = '<li class="page"><a href="' . $this->generateUrl($i) . '"' . $class . '>' . $i . '</a></li>';
            }
        }

        if ($page+3 < $pages) {
            if ($page+4 < $pages) {
                $result[] = '<li class="spacer spacer-next">...</li>';
            }
            $result[] = '<li class="last"><a href="' . $this->generateUrl($pages) . '" class="last">' . $pages . '</a></li>';
        }

        if ($page < $pages && $pages > 1) {
            $result[] = '<li class="next"><a href="' . $this->generateUrl($page+1) . '" class="next">»</a></li>';
        }

        return '<div class="Pagination"><ol class="Pagination">' . implode(PHP_EOL, $result) . '</ol></div>';
    }

    protected function generateUrl($page)
    {
        return str_replace('000', $page, $this->getUrl());
    }
}
