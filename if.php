<?php

/**
 * If 18.10.1 Arcane Helpers
 * https://github.com/MEDIA76/arcane/
**/

return function($conditional, $return, $wrap = '%s') {
  if($conditional) {
    if($wrap != '%s' && !strpos($wrap, '%s')) {
      $wrap = $wrap . '="%s"';
    }

    return sprintf(" {$wrap}", $return);
  }
}

?>