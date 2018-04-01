<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Script\Super);

$str = <<<EOD
This is ^^Superscripted text^^
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);