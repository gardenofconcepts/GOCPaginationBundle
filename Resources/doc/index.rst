Documentation
=============


Configuration
-------------

add to your deps file::

    [DoctrineExtensions]
        git=http://github.com/beberlei/DoctrineExtensions.git
        target=doctrine-extensions

    [GOCPaginationBundle]
        git=http://github.com/dennisoehme/GOCPaginationBundle.git
        target=/bundles/GOC/PaginationBundle

modify your autoload.php for autoloading bundle and doctrine extensions::

    'DoctrineExtensions' => __DIR__.'/../vendor/doctrine-extensions/lib',
    'GOC'                => __DIR__.'/../vendor/bundles',

load GOCPaginationBundle in your AppKernel.php::

    new GOC\PaginationBundle\GOCPaginationBundle();

run::

    php bin/vendors install


Routing
-------

every route for pagination requires one parameter called "page" (default = 1)::

    items:
        pattern:   /items/{page}
        defaults:  { _controller: AcmeExampleBundle:Item:list, page: 1 }


Controller
----------

in your controller create a pagination from factory but only the first parameter is required:

* $query (an instance of \Doctrine\ORM\Query)
* the second parameter ($items) is the count of items per page (default = 50)
* the last one ($page) is the actual page number (is optional because page number will be detect automatically from routing)

ItemController::

    /**
     * @Template()
     */
    public function listAction($page = 1)
    {
        $query = $this->getDoctrine()
                ->getRepository('AcmeExampleBundle:Item')
                ->getItems() // will return an \Doctrine\ORM\Query
                ->getQuery();

        $pagination = $this->get('goc_pagination.factory')->create($query, 50, $page);

        return array('items' => $pagination->getQuery()->getResult(), // or simpler: $pagination->getResult()
                     'pagination' => $pagination);
    }

Template (Twig)
---------------

the bundle includes a twig function called "pagination" that requires as parameter the pagination object from controller:::

    {{ pagination(pagination) }}

generates::
    
    <div class="Pagination">
        <ol class="Pagination">
            <li class="prev"><a href="/items/2" class="prev">«</a></li>
            <li class="page"><a href="/items/1">1</a></li>
            <li class="page"><a href="/items/2">2</a></li>
            <li class="page"><a href="/items/3" class="active">3</a></li>
            <li class="page"><a href="/items/4">4</a></li>
            <li class="page"><a href="/items/5">5</a></li>
            <li class="page"><a href="/items/6">6</a></li>
            <li class="spacer spacer-next">...</li>
            <li class="last"><a href="/items/13" class="last">13</a></li>
            <li class="next"><a href="/items/4" class="next">»</a></li>
        </ol>
    </div>

the pagination object has some getter methods::

    {{ pagination.items }} Items found | Page {{ pagination.page }} / {{ pagination.pages }} Pages