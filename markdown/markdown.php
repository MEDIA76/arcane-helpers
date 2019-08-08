<?php

/**
 * Markdown 19.08.1 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return function($content) {
  if(!is_array($content)) {
    $content = trim($content);

    if(substr($content, -2) == 'md') {
      if(is_file($path = path($content, true))) {
        $content = file_get_contents($path);
      } else {
        $content = $content;
      }
    }

    $content = explode("\n", $content);
  }

  $content = array_values(array_filter($content));

  foreach($content as $index => $line) {
    if(preg_match('/^(\s+)?([\#+|\+|\-|\*]+)\s+(.+)/', $line, $slice)) {
      if(preg_match('/^(\#){1,6}+$/', $slice[2])) {
        $length = strlen($slice[2]);
        $format = "<h{$length}>%s</h{$length}>";
      } else if(in_array($slice[2], ['+', '-', '*'])) {
        $format = "<li>%s</li>";

        if(!isset($primary) || !isset($secondary)) {
          $list = $slice[2] == '*' ? 'ul' : 'ol';

          if($slice[2] == '-') {
            $list = "{$list} type=\"A\"";
          }

          if(!isset($primary)) {
            $format = "<{$list}>{$format}";
            $primary = $slice[2];
          }

          if(!isset($secondary)) {
            if(ltrim($content[$index])[0] != $primary) {
              $format = "<{$list}>{$format}";
              $secondary = $slice[2];
            }
          }
        }

        if(trim($content[$index + 1][0])) {
          if(ltrim($content[$index + 1])[0] != $primary) {
            $list = $primary == '*' ? 'ul' : 'ol';
            $format = "{$format}</{$list}>";

            unset($primary);
          }

          if(isset($secondary)) {
            if(ltrim($content[$index + 1])[0] != $secondary) {
              $list = $secondary == '*' ? 'ul' : 'ol';
              $format = "{$format}</{$list}>";

              unset($secondary);
            }
          }
        } else if(!isset($content[$index + 1])) {
          $format = "{$format}</{$list}>";
        }
      }

      $line = $slice[3];
    } else {
      if(trim($line) == '---') {
        $format = '<hr />';
      } else {
        $format = '<p>%s</p>';
      }
    }

    foreach([
      '\*(.+)\*' => '<strong>$1</strong>',
      '\_(.+)\_' => '<em>$1</em>',
      '\~(.+)\~' => '<strike>$1</strike>',
      '\`(.+)\`' => '<code>$1</code>',
      '\!\[(.*)\]\((.*)\)' => '<img src="$2" alt="$1" />',
      '\[(.*)\]\((.*)\)' => '<a href="$2">$1</a>'
    ] as $pattern => $replacement) {
      $line = preg_replace("/{$pattern}/", $replacement, $line);
    }

    $results[] = sprintf($format, trim($line));
  }

  return implode($results);
};

?>