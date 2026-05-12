<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/_price-compare.php");

$template = new Template();
$pc = new PriceCompare();

$template->returnHeader();

echo $template->IncludeCSS("/_css/price-compare.css");
?>

<h2>Watchwork Sproket</h2>

<?php

$list1 = array();
array_push($list1, (object)[
    'id' => 44956,
    'amount' => 1,
    'wiki' => 'Superior_Rune_of_Tormenting'
]);

$list2 = array();
array_push($list2, (object)[
    'id' => 44944,
    'amount' => 1,
    'wiki' => 'Superior_Sigil_of_Bursting'
]);

$list3 = array();
array_push($list3, (object)[
    'id' => 44950,
    'amount' => 1,
    'wiki' => 'Superior_Sigil_of_Malice'
]);

?>

<div data-id="container">
    <div data-col>
        <h3>Option 1</h3>
        <div data-group>
            <?php $pc->build($list1); ?>
        </div>
    </div>
    <div data-col>
        <h3>Option 2</h3>
        <div data-group>
            <?php $pc->build($list2); ?>
        </div>
    </div>
    <div data-col>
        <h3>Option 3</h3>
        <div data-group>
            <?php $pc->build($list3); ?>
        </div>
    </div>
</div>

<?php

echo $template->IncludeJS("/_js/price-compare.js");

$template->returnFooter();
