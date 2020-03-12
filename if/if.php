<?php

/**
 * If 19.06.1 Arcane Helper
 * MIT https://helpers.arcane.dev
**/

return function($conditional, $return = null, $format = "\x20%s") {
  if($conditional) {
    if(strpos($format, '%s') === false) {
      $format = "\x20{$format}=\"%s\"";
    }

    return sprintf($format, $return ?? $conditional);
  }
};

?>