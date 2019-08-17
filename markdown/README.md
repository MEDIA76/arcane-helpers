## Markdown Function

> `$markdown($content)`

``` php
<?= $markdown('## Heading'); ?>

<?= $markdown('filename.md'); ?>
```

``` txt
# Heading 1
## Heading 2
### Heading 3
#### Heading 4
##### Heading 5
###### Heading 6
--- Horizontal Ruler
* Bulleted List
+ Numbered List
- Alphabetical List
*Bold*
_Italic_
~Strikethrough~
`code`
![Alternate Text](Image URL)
[Link Text](Link URL)
> Block Quotation
    Preformatted Text
```