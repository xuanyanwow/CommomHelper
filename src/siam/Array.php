<?php


namespace Siam;



class Array{
  
  public static function page($array, $page, $limit)
  {
    
    
    $needOffset = ($page - 1) * $limit;
    
    return [
        'total'    => count($array),
        'data'     => array_slice($array, $needOffset, $limit)
    ];
  }
}
