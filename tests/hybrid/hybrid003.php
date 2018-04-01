<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Twitter\Handle);
$visitors->add(new \CommonMark\Visitors\Twitter\Tweet);

$str = <<<EOD
@krakjoe
[tweet](https://twitter.com/official_php/status/903310416549339136)
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);