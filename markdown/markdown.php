<?php

/**
 * Markdown 19.03.1 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return function($content) {
  $content = trim($content);

  if(substr($content, -2) == 'md') {
    if(is_file($content = path($content, true))) {
      $content = file_get_contents($content);
    }
  }

  $content = array_values(array_filter(explode("\n", $content)));

  foreach($content as $index => $line) {
    if(preg_match('/(\s+)?([\#+|\+|\-|\*]+)\s+(.+)/', $line, $slice)) {
      if(preg_match('/^(\#){1,6}+$/', $slice[2])) {
        $length = strlen($slice[2]);
        $pattern = "<h{$length}>%s</h{$length}>";
      } else if(in_array($slice[2], ['+', '-', '*'])) {
        $pattern = "<li>%s</li>";

        if(!isset($primary) || !isset($secondary)) {
          $list = $slice[2] == '*' ? 'ul' : 'ol';

          if($slice[2] == '-') {
            $list = "{$list} type=\"A\"";
          }

          if(!isset($primary)) {
            $pattern = "<{$list}>{$pattern}";
            $primary = $slice[2];
          }

          if(!isset($secondary)) {
            if(ltrim($content[$index])[0] != $primary) {
              $pattern = "<{$list}>{$pattern}";
              $secondary = $slice[2];
            }
          }
        }

        if(trim($content[$index + 1][0])) {
          if(ltrim($content[$index + 1])[0] != $primary) {
            $list = $primary == '*' ? 'ul' : 'ol';
            $pattern = "{$pattern}</{$list}>";

            unset($primary);
          }

          if(isset($secondary)) {
            if(ltrim($content[$index + 1])[0] != $secondary) {
              $list = $secondary == '*' ? 'ul' : 'ol';
              $pattern = "{$pattern}</{$list}>";

              unset($secondary);
            }
          }
        } else if(!isset($content[$index + 1])) {
          $pattern = "{$pattern}</{$list}>";
        }
      }

      $line = $slice[3];
    } else {
      if($line == '---') {
        $pattern = '<hr />';
      } else {
        $pattern = '<p>%s</p>';
      }
    }

    $return[$index] = sprintf($pattern, trim($line));
  }

  return implode($return);
};

?>