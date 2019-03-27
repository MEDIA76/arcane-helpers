## If Function

> `$if($conditional, $return = null, $format = ' %s')`

``` php
<a href="" class="anchor<?= $if(path(1) == 'path', 'current'); ?>">Anchor</a>

<a href=""<?= $if(path(1) == 'path', 'current', 'class'); ?>>Anchor</a>

<h1><?= $if(!is_null($text), 'Text', '%s '); ?>Heading</h1>

<h1>Heading<?= $if($text ?? false); ?></h1>
```