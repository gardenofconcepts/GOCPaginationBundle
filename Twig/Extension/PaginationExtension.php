<?php

namespace GOC\PaginationBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GOC\PaginationBundle\Pagination;
    
class PaginationExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'pagination'        => new \Twig_Function_Method($this, 'renderPagination', array('is_safe' => array('html'))),
            'pagination_items'  => new \Twig_Function_Method($this, 'getItems', array('is_safe' => array('html'))),
            'pagination_pages'  => new \Twig_Function_Method($this, 'getPages', array('is_safe' => array('html'))),
        );
    }

    public function renderPagination(Pagination $pagination, array $params = array())
    {
        return $pagination->render($params);
    }

    public function getItems(Pagination $pagination)
    {
        return (int)$pagination->getItems();
    }

    public function getPages(Pagination $pagination)
    {
        return (int)$pagination->getPages();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pagination';
    }
}
