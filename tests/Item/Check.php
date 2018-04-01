<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Item\Check);

$str = <<<EOD
This is empty checkbox: [ ], 
This is crossed checkbox: [-],
This is checked checbox: [x], 
This is checked checkbox too: [X], 
This aperently is also a checked checkbox: [+]
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);