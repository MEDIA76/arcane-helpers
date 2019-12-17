<?php

/**
 * If 19.12.1 Arcane Helper
 * Copyright 2017-2019 Joshua Britt
 * MIT https://helpers.arcane.dev
**/

return function($content, $reference, $target = null) {
  if(!is_null($target)) {
    $target = "\x20target=\"{$target}\"";
  }

  return "<a href=\"{$reference}\"{$target}>{$content}</a>";
};

?>