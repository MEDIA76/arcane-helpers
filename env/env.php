<?php

/**
 * Env 19.05.1 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

if(file_exists('.gitignore')) {
  $gitignore = array_map('trim', file('.gitignore'));

  if(!in_array('.env', array_filter($gitignore))) {
    file_put_contents('.gitignore', "\n.env", FILE_APPEND);
  }
}

if(!file_exists('.env')) {
  touch('.env');
} else {
  $env = array_map(function($line) {
    if(substr(ltrim($line), 0, 1) != '#') {
      return str_replace(' ', '', $line);
    }
  }, file('.env'));

  foreach(array_filter($env) as $variable) {
    putenv($variable);
  }
}

return function($key) {
  if(!is_array($key)) {
    return getenv($key);
  } else {
    return array_map(function($key) {
      return getenv($key);
    }, $key);
  }
};

?>