<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('pagination'))
{
       
    function pagination($per_page, $page, $url, $total)
    {
        $CI =& get_instance();
           $adjacents = "1";
        intval($per_page);
        intval($page);
        intval($total);
        $page = ($page == 0 ? 1 : $page);
        $start = ($page - 1) * $per_page;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total/$per_page);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if($lastpage > 1)
        {
           $pagination .= "<ul class='pagination'>";
                 $pagination .= "<li class='details'>Stranica $page od $lastpage</li>";

            if($page>1){
                $pagination.= "<a href='{$url}1'><li class='first_page' data-page='1'>Početak</li></a>";
                $pagination.= "<a href='{$url}$prev'><li class='prev_page' data-page='{$prev}'>Prethodna</li></a>";
            }else{
                $pagination.= "<li class='first_page'>Početak</li>";
                $pagination.= "<li class='prev_page'>Prethodna</li>";
            }

            if ($lastpage < 7 + ($adjacents * 2))
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<a class='current'><li class='current_page'>$counter</li></a>";
                    else
                        $pagination.= "<a href='{$url}$counter'><li class='page' data-page='{$counter}'>$counter</li></a>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='current'><li class='current_page'>$counter</li></a>";
                        else
                            $pagination.= "<a href='{$url}$counter'><li class='page' data-page='{$counter}'>$counter</li></a>";
                    }
                    $pagination.= "<li class='dot'>...</li>";
                    $pagination.= "<a href='{$url}$lpm1'><li class='page' data-page='{$lpm1}'>$lpm1</li></a>";
                    $pagination.= "<a href='{$url}$lastpage'><li class='page' data-page='{$lastpage}'>$lastpage</li></a>";
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href='{$url}1'><li class='page' data-page='1'>1</li></a>";
                    $pagination.= "<a href='{$url}2'><li class='page' data-page='2'>2</li></a>";
                    $pagination.= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='current'><li class='page'>$counter</li></a>";
                        else
                            $pagination.= "<a href='{$url}$counter'><li class='page' data-page='{$counter}'>$counter</li></a>";
                    }
                    $pagination.= "<li class='dot'>..</li>";
                    $pagination.= "<a href='{$url}$lpm1'><li class='next_page' data-page='{$lpm1}'>$lpm1</li></a>";
                    $pagination.= "<a href='{$url}$lastpage'><li class='last_page' data-page='{$lastpage}'>$lastpage</li></a>";
                }
                else
                {
                    $pagination.= "<a href='{$url}1'><li class='page' data-page='{$url}'>1</li></a>";
                    $pagination.= "<a href='{$url}2'><li class='page' data-page='{$url}'>2</li></a>";
                    $pagination.= "<li class='dot'>..</li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='current'><li class='page'>$counter</li></a>";
                        else
                            $pagination.= "<a href='{$url}$counter'><li class='page' data-page='{$counter}'>$counter</li></a>";
                    }
                }
            }
            
            if ($page < $lastpage){
                $pagination.= "<a href='{$url}$next'><li class='next_page' data-page='{$next}'>Sledeća</li></a>";
                $pagination.= "<a href='{$url}$lastpage'><li class='last_page' data-page='{$lastpage}'>Kraj</li></a>";
            }else{
               $pagination.= "<li class='next_page'>Sledeća</li>";
               $pagination.= "<li class='last_page'>Kraj</li>";
            }
            $pagination.= "</ul>\n";
        }
        return $pagination;
    }
}