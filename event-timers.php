<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>Event Timers</h2>

<h3>Next</h3>

<table id="next">
    <thead>
        <tr>
            <th>Section</th>
            <th>Name</th>
            <th>Next</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<template id="template_next">
    <tr>
        <td data-id="section"></td>
        <td data-id="name">
            <a data-id="val"target="_blank"></a>
            <p>
                <a data-id="complete" data-value href="javascript:void(0)"></a>
            </p>
        </td>
        <td data-id="next"></td>
    </tr>
</template>

<template id="template_next_section">
    <tr class="divider">
        <th colspan="3"></th>
    </tr>
</template>

<h3>All Events</h3>

<table id="all">
    <thead>
        <tr>
            <th>Name</th>
            <th>Next</th>
            <th>Others</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<template id="template_all">
    <tr>
        <td data-id="name">
            <a data-id="val" target="_blank"></a>
            <p>
                <a data-id="complete" data-value href="javascript:void(0)"></a>
            </p>
        </td>
        <td data-id="next"></td>
        <td data-id="others"></td>
    </tr>
</template>

<template id="template_all_section">
    <tr class="divider">
        <th colspan="3" data-id="section"></th>
    </tr>
</template>

<?php

$template->returnFooter();
