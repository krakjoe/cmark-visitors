<?php
include '../../vendor/autoload.php';


use CommonMark\Visitors;

$visitors = new Visitors;
$visitors->add(new \CommonMark\Visitors\Item\Check);

$str = <<<EOD
This is a list with checkboxes:

 * 1st Item [+]
 * 2nd Item [x]
 * 34t Item [X]
 * 4th Item [-]
 * 5th Item [ ]

 Not an Item [x]
EOD;



$doc = CommonMark\Parse(
$str
);

$doc->accept($visitors);

echo CommonMark\Render\HTML($doc);