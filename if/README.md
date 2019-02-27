## If Function

> `$if($conditional, $return, $wrap = '%s')`

``` php
<a href="" class="anchor<?= $if(path(1) == 'path', 'current'); ?>">Anchor</a>

<a href=""<?= $if(path(1) == 'path', 'current', 'class'); ?>>Anchor</a>

<h1>Heading<?= $if(!is_null($small), 'Text', '<small>%s</small>'); ?></h1>
```