<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>Wallet</h2>

<table>
    <thead>
        <tr>
            <th></th>
            <th>Currency</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<template id="template">
    <tr>
        <td class="small-col" data-id="icon"><a data-id="url" target="_blank"><img class="icon"></a></td>
        <td data-id="currency"></td>
        <td data-id="value"></td>
    </tr>
</template>


<?php

$template->returnFooter();
