## Languages Array

> `$languages[$code]`

``` php
<select name="language">
  <?php foreach(LOCALES as $locales) { ?>
    <?php foreach($locales as $locale) { ?>
      <option value="<?= $locale['URI']; ?>"><?= $languages[$locale['LANGUAGE']]; ?></option>
    <?php } ?>
  <?php } ?>
</select>
```