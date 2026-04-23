<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

?>

<h2>To Do Lists</h2>

<h3>Resets</h3>

<table>
    <thead>
        <tr>
            <th>Daily</th>
            <th>Weekly</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="daily-time"></td>
            <td id="weekly-time"></td>
        </tr>
        <tr>
            <td id="daily-remaining"></td>
            <td id="weekly-remaining"></td>
        </tr>
    </tbody>
</table>

<h3>Auto Tracking</h3>

<?php

$template->returnFooter();
