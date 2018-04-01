<?php
include '../../vendor/autoload.php';

use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Twitter\Handle);

$doc = CommonMark\Parse(<<<EOD
@krakjoe
EOD
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);