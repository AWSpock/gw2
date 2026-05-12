<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/_price-compare.php");

$template = new Template();
$pc = new PriceCompare();

$template->returnHeader();

echo $template->IncludeCSS("/_css/price-compare.css");
?>

<h2>Iron</h2>

<?php

$list1 = array();
array_push($list1, (object)[
    'id' => 19699,
    'amount' => 3,
    'wiki' => ''
]);

$list2 = array();
array_push($list2, (object)[
    'id' => 19683,
    'amount' => 1,
    'wiki' => ''
]);

$list3 = array();
array_push($list3, (object)[
    'id' => 19688,
    'amount' => 1,
    'wiki' => ''
]);
array_push($list3, (object)[
    'id' => 19750,
    'amount' => 1,
    'wiki' => '',
    'cost' => 16
]);

?>

<div data-id="container">
    <div data-col>
        <h3>Raw</h3>
        <div data-group>
            <?php $pc->build($list1); ?>
        </div>
    </div>
    <div data-col>
        <h3>Iron Ingot</h3>
        <div data-group>
            <?php $pc->build($list2); ?>
        </div>
    </div>
    <div data-col>
        <h3>Steel Ingot</h3>
        <div data-group>
            <?php $pc->build($list3); ?>
        </div>
    </div>
</div>

<?php

echo $template->IncludeJS("/_js/price-compare.js");

$template->returnFooter();
