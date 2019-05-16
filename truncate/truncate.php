<?php

/**
 * Truncate 19.03.2 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return function($string, $limit = 100, $suffix = '...') {
  if(mb_strlen($string) > $limit) {
    $string = mb_substr($string, 0, $limit) . $suffix;
  }

  return $string;
};

?>