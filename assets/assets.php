<?php

/**
 * Assets 19.06.2 Arcane Helper
 * https://github.com/MEDIA76/arcane
**/

return function($resources, $timestamp = false) {
  $resources = is_array($resources) ? $resources : [$resources];
  $resources = array_filter(array_merge([''], $resources));
  $types = [
    'css' => [
      'name' => 'STYLES',
      'html' => '<link href="%s" rel="stylesheet" />'
    ],

    'js' => [
      'name' => 'SCRIPTS',
      'html' => '<script src="%s"></script>'
    ]
  ];

  foreach($types as $extension => $type) {
    if($index = array_search($type['name'], $resources)) {
      $assets = array_merge([
        (defined('LAYOUT') ? LAYOUT : SET['LAYOUT']) . ".{$extension}"
      ], preg_filter('/$/', ".{$extension}", PATHS));

      if(defined($type['name'])) {
        array_push($assets, constant($type['name']));
      }

      array_splice($resources, $index - 1, 1, $assets);
    }
  }

  foreach($resources as $resource) {
    if(strpos($resource, '.')) {
      $extension = substr($resource, strrpos($resource, '.') + 1);

      if(array_key_exists($extension, $types)) {
        extract($types[$extension]);

        if(file_exists(path([$name, $resource], true))) {
          if($timestamp) {
            $resource = "{$resource}?timestamp=" . time();
          }

          $results[] = sprintf($html, path([$name, $resource]));
        }
      }
    }
  }

  return implode($results ?? []);
};

?>