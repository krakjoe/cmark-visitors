<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Script\Sub);
$visitors->add(new \CommonMark\Visitors\Script\Super);

$str = <<<EOD
This is ~~subscript~~ and 
 this is ^^superscript^^
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);