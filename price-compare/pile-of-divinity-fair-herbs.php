<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/_price-compare.php");

$template = new Template();
$pc = new PriceCompare();

$template->returnHeader();

echo $template->IncludeCSS("/_css/price-compare.css");
?>

<h2>Pile of Divinity Fair Herbs</h2>

<?php

$list1 = array();
array_push($list1, (object)[
    'id' => 12243,
    'amount' => 1,
    'wiki' => ''
]);
array_push($list1, (object)[
    'id' => 12246,
    'amount' => 2,
    'wiki' => ''
]);
array_push($list1, (object)[
    'id' => 12248,
    'amount' => 1,
    'wiki' => ''
]);
array_push($list1, (object)[
    'id' => 12335,
    'amount' => 1,
    'wiki' => ''
]);

$list2 = array();
array_push($list2, (object)[
    'id' => 12272,
    'amount' => 1,
    'wiki' => ''
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
        <h3>Refined</h3>
        <div data-group>
            <?php $pc->build($list2); ?>
        </div>
    </div>
</div>

<?php

echo $template->IncludeJS("/_js/price-compare.js");

$template->returnFooter();
