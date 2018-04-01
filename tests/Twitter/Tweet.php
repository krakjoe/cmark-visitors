<?php
include '../../vendor/autoload.php';

use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Twitter\Tweet);

$doc = CommonMark\Parse(<<<EOD
[tweet](https://twitter.com/official_php/status/903310416549339136)
EOD
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);