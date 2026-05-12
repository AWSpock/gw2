<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/_price-compare.php");

$template = new Template();
$pc = new PriceCompare();

$template->returnHeader();

echo $template->IncludeCSS("/_css/price-compare.css");
?>

<h2>Elder Wood</h2>

<?php

$list1 = array();
array_push($list1, (object)[
    'id' => 19722,
    'amount' => 3,
    'wiki' => ''
]);

$list2 = array();
array_push($list2, (object)[
    'id' => 19709,
    'amount' => 1,
    'wiki' => ''
]);

$list3 = array();
array_push($list3, (object)[
    'id' => 19757,
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
    <div data-col>
        <h3>Dowel</h3>
        <div data-group>
            <?php $pc->build($list3); ?>
        </div>
    </div>
</div>

<?php

echo $template->IncludeJS("/_js/price-compare.js");

$template->returnFooter();
