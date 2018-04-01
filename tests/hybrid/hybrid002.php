<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Script\Insert);
$visitors->add(new \CommonMark\Visitors\Script\Delete);

$str = <<<EOD
This is ++inserted in++ and 
 this is --crossed out--
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);