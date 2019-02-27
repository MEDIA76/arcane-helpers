<?php

/**
 * Truncate 19.02.2 Arcane Helpers
 * https://github.com/MEDIA76/arcane/
**/

return function($string, $limit = 100, $suffix = '...') {
  return substr($string, 0, $limit) . $suffix;
}

?>