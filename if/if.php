<?php

/**
 * If 19.03.1 Arcane Helpers
 * https://github.com/MEDIA76/arcane
**/

return function($conditional, $return = null, $format = "\x20%s") {
  if($conditional) {
    if(strpos($format, '%s') === false) {
      $format = "{$format}=\"%s\"";
    }

    return sprintf($format, $return ?? $conditional);
  }
}

?>