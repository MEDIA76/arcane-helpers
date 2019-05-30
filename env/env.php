<?php

/**
 * Env 19.05.2 Arcane Helper
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
  foreach(file('.env') as $line) {
    if(substr(ltrim($line), 0, 1) != '#') {
      $settings[] = str_replace(' ', '', $line);
    }
  }

  foreach(array_filter($settings) as $setting) {
    putenv($setting);
  }
}

return function($variable) {
  if(!is_array($variable)) {
    return getenv($variable);
  } else {
    return array_map('getenv', $variable);
  }
};

?>