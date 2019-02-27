## Languages Array

> `$languages[code]`

``` php
<select name="">
  <?php foreach(LOCALES as $locales) { ?>
    <?php foreach($locales as $locale) { ?>
      <option value=""><?= $languages[$locale['LANGUAGE']]; ?></option>
    <?php } ?>
  <?php } ?>
</select>
```