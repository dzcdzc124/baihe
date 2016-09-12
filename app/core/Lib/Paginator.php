<?php

namespace App\Lib;

use Phalcon\Paginator\Adapter;
use Phalcon\Paginator\AdapterInterface;
use Phalcon\Paginator\Exception;

class Paginator extends Adapter implements AdapterInterface
{
    protected $_config = null;

    public function __construct(array $config)
    {
        $this->_config = $config;

        if (isset($config['limit']))
            $this->_limitRows = $config['limit'];

        if (isset($config['page']))
            $this->_page = $config['page'];
    }

    public function getPaginate()
    {
        $limit = intval($this->_limitRows);
        $pageNumber = intval($this->_page);

        if ( ! $pageNumber)
            $pageNumber = 1;

        if ($pageNumber == 1)
            $before = 1;
        else
            $before = $pageNumber - 1;

        $query = $this->_config['query'];
        $totalQuery = clone $query;

        $totalQuery->columns('COUNT(*) [rowcount]')->limit(null);
        $totalRow = $totalQuery->execute()->getFirst();
        $rowCount = $totalRow ? $totalRow->rowcount : 0;
        $totalPages = intval(ceil($rowCount / $limit));

        if ($pageNumber < $totalPages)
            $next = $pageNumber + 1;
        else
            $next = $totalPages;

        $number = $limit * ($pageNumber - 1);
        if ($number < $limit)
            $query->limit($limit);
        else
            $query->limit($limit, $number);

        $items = $query->execute();

        $page = new \stdClass;
        $page->items = $items;
        $page->first = 1;
        $page->before = $before;
        $page->current = $pageNumber;
        $page->last = $totalPages;
        $page->next = $next;
        $page->total_pages = $totalPages;
        $page->total_items = $rowCount;
        $page->limit = $limit;

        return $page;
    }
}