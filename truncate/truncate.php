<?php

/**
 * Truncate 19.03.1 Arcane Helpers
 * https://github.com/MEDIA76/arcane
**/

return function($string, $limit = 100, $suffix = '...') {
  return mb_substr($string, 0, $limit) . $suffix;
}

?>