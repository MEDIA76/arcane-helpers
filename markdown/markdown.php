<?php

/**
 * Markdown 19.08.2 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return function($content) {
  if(!is_array($content)) {
    $content = trim($content);

    if(substr($content, -2) === 'md') {
      if(is_file($path = path($content, true))) {
        $content = file_get_contents($path);
      }
    }

    $content = explode("\n", $content);
  }

  $content = array_values(array_filter($content));

  foreach($content as $index => $line) {
    $next = isset($content[$index + 1]) ? $content[$index + 1] : false;

    if(ltrim($line)[0] === '>') {
      $line = substr($line, strpos($line, '>') + 1);

      if(!isset($quote)) {
        $quote = 'blockquote';
        $results[] = "<{$quote}>";
      }
    }

    if(preg_match('/^[\s]*(\#{1,6}|\+|\-|\*)\s+(.+)$/', $line, $slice)) {
      if($slice[1][0] === '#') {
        $length = strlen($slice[1]);
        $format = "<h{$length}>%s</h{$length}>";
      } else if(in_array($slice[1], ['+', '-', '*'])) {
        $format = "<li>%s</li>";

        if(!isset($primary) || !isset($secondary)) {
          $list = $slice[1] === '*' ? 'ul' : 'ol';

          if($slice[1] === '-') {
            $list = "{$list} type=\"A\"";
          }

          if(!isset($primary)) {
            $format = "<{$list}>{$format}";
            $primary = $slice[1];
          }

          if(!isset($secondary)) {
            if(ltrim($line)[0] !== $primary) {
              $format = "<{$list}>{$format}";
              $secondary = $slice[1];
            }
          }
        }

        if(!$next) {
          $format = "{$format}</{$list}>";
        } else {
          if(trim($next[0])) {
            if(ltrim($next)[0] !== $primary) {
              $list = $primary === '*' ? 'ul' : 'ol';
              $format = "{$format}</{$list}>";

              unset($primary);
            }

            if(isset($secondary)) {
              if(ltrim($next)[0] !== $secondary) {
                $list = $secondary === '*' ? 'ul' : 'ol';
                $format = "{$format}</{$list}>";

                unset($secondary);
              }
            }
          }
        }
      }

      $line = $slice[2];
    } else {
      if(trim($line) === '---') {
        $format = '<hr />';
      } else {
        $format = '<p>%s</p>';
      }
    }

    foreach([
      '*' => ['\*([^ \*+][^ \*+]*[^ \*+]?)\*', '<strong>$1</strong>'],
      '_' => ['\_([^ \_+][^ \_+]*[^ \_+]?)\_', '<em>$1</em>'],
      '~' => ['\~([^ \~+][^ \~+]*[^ \~+]?)\~', '<strike>$1</strike>'],
      '`' => ['\`([^ \`+][^ \`+]*[^ \`+]?)\`', '<code>$1</code>'],
      '![' => ['\!\[(.*)\]\((.*)\)', '<img src="$2" alt="$1" />'],
      '](' => ['\[(.*)\]\((.*)\)', '<a href="$2">$1</a>'],
    ] as $search => $regex) {
      if(strpos($line, $search) !== false) {
        $line = preg_replace("/{$regex[0]}/", $regex[1], $line);
      }
    }

    $results[] = sprintf($format, trim($line));

    if(isset($quote)) {
      if(!$next) {
        $results[] = "</{$quote}>";
      } else {
        if(ltrim($next)[0] !== '>') {
          $results[] = "</{$quote}>";

          unset($quote);
        }
      }
    }
  }

  return implode($results);
};

?>