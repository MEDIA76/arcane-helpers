<?php

/**
 * Truncate 19.02.1 Arcane Helpers
 * https://github.com/MEDIA76/arcane/
**/

return function($string, $limit) {
  return substr($string, 0, $limit) . '...';
}

?>