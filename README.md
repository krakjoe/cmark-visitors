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

### Table

```
-------------------------------------
| head one   |       head two       |
-------------------------------------
| row one      | row one head two   | 
| row two      | row two head two   | 
-------------------------------------
```

### Twitter
  * CommonMark\Visitors\Twitter\Handle:  `@handle`:            autolink twitter handle
  * CommonMark\Visitors\Twitter\Tweet:   `status`:             twitter api returns HTML

### GitHub
  * CommonMark\Visitors\GitHub\Project:        `[github:user/project]`:          autolink to github project
  * CommonMark\Visitors\GitHub\Issue:          `[github:user/project#num]`:      autolink to github issue
  * CommonMark\Visitors\GitHub\PullRequest:    `[github:user/project#pull/num]`: autolink to github pull request
  * CommonMark\Visitors\GitHub\Gist:           `[gist:user/gist]`:               embed gist

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
