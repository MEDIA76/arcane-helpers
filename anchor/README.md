## Anchor Function

> `$anchor($content, $reference, $attributes = [])`

``` php
<?= $anchor('About', path('/about/')); ?>

<?= $anchor('GitHub', 'https://github.com', [
  'target' => '_blank'
]); ?>
```