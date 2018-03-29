# CommonMark\Visitors
Visitors for CommonMark implementing some useful AST transformations

## Implemented

The following may change rapidly ...

### Script
  * CommonMark\Visitors\Script\Super:    `^^super script^^`:   &lt;sub&gt;
  * CommonMark\Visitors\Script\Sub:      `~~sub script~~`:     &lt;sup&gt;
  * CommonMark\Visitors\Script\Delete:   `--del--`:            &lt;del&gt;
  * CommonMark\Visitors\Script\Insert:   `++ins++`:            &lt;ins&gt;

### Item Checks
  * CommonMark\Visitors\Item\Check
    * `[ ]` empty
    * `[x]` checked
    * `[X]` checked
    * `[+]` checked
    * `[-]` crossed

### Twitter
  * CommonMark\Visitors\Twitter\Handle:  `@handle`:            &lt;a href="http://twitter.com/handle"&gt;
  * CommonMark\Visitors\Twitter\Tweet:   `status`:             twitter api returns HTML

#### Usage

```php
<?php
use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Twitter\Handle);

$doc = CommonMark\Parse(<<<EOD
@krakjoe
EOD
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);
?>
```

Will output `<p><a href="http://twitter.com/krakjoe">@krakjoe</a></p>`

#### Testing

Not yet ... it's coming ...
