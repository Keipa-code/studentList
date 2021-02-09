<?php

namespace Src\Model;
use Src\Model\Tdgw;

error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors',1);
error_reporting(-1);

class LinkGenerator
{

    public function pageLinkGenerate($var, $pageNumber)
        {
            $linkData=array();
            if (isset($var['sort'])) {
                $linkData['sort'] = $var['sort'];
            }
            if (isset($var['search'])) {
                $linkData['search'] = $var['search'];
            }
            $linkData['page'] = $pageNumber;

            $link = "/index.php?" . http_build_query($linkData);
            return $link;

        }

    public function sortLinkGenerator($var, $key)
    {
        $linkData=array();
        $linkData ['sort'] = $key;
        if (isset($var['search'])) {
            $linkData['search'] = $var['search'];
        }
        if (isset($var['page'])) {
            $linkData['page'] = $var['page'];
        }
        $link = "/index.php?" . http_build_query($linkData);
        return $link;
    }
}