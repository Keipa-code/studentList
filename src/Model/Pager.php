<?php
namespace Src\Model;

use Src\Model\Tdgw;

Class Pager
{
    protected $mapper;
    public $currentPageNumber;
    public $pageNumbers;
    public $totalPageCount;

    public function __construct()
    {
        $this->mapper=new Tdgw();

    }


    public function totalPagesCount($rows){
        $rowCount = count($rows);

        //$rowCount = (int)implode("",$rowCount);
        if ($rowCount > 50) {
            $pagesCount = (int)ceil($rowCount / 50);
        }else{
            $pagesCount = 1;
        }

        return $pagesCount;
    }

    public function getPageNumbers($page, $pageCount){
        $ty = $pageCount - $page;

        if($pageCount - $page < 5 && $pageCount - $page >= 0 && $page !== 1){
            $page = $pageCount - 4;

        }
        for($i = 1; $i <=5; $i++){
            $pageNumbers[]= $page;
            if($page == $pageCount){
                break;
            }
            $page++;

        }

        $this->pageNumbers = $pageNumbers;
    }

    public function getCurrentPageNumber($page){
        $x = array_key_exists('page', $page) ? strval($page['page']) : 1;
        $this->currentPageNumber = (int)$x;
    }
}