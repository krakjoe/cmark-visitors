<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Script\Insert);

$str = <<<EOD
This is ++inserted(underlined) text++
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);