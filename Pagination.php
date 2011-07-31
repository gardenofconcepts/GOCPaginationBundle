<?php

namespace GOC\PaginationBundle;

interface Pagination
{
    public function getPages();
    public function getItems();
    public function getUrl();
    public function setUrl($url);
    public function render();
}
