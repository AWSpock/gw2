<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

$nowUtc = new DateTime("now", new DateTimeZone("UTC"));

$events = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_data/event-timers.json"));
$nextEvents = [];

foreach ($events as $event) {
    if (property_exists($event, "times")) {
        foreach ($event->times as $time) {
            $dt = new DateTime("today $time", new DateTimeZone("UTC"));
            if ($dt <= $nowUtc)
                $dt->modify("+1 day");
            array_push($event->occurrences, $dt);
        }
        usort($event->occurrences, function ($a, $b) {
            if ($a == $b) return 0;
            return ($a < $b) ? -1 : 1;
        });
        array_push($nextEvents, $event);
    }
}

usort($nextEvents, function ($a, $b) {
    if ($a->occurrences[0] == $b->occurrences[0]) return 0;
    return ($a->occurrences[0] < $b->occurrences[0]) ? -1 : 1;
});

?>

<h2>Event Timers</h2>

<ul>
    <li><a href="#next">Next Events</a></li>
    <li><a href="#all">All Events</a></li>
</ul>

<h3 id="next">Next</h3>

<table>
    <thead>
        <tr>
            <th>Section</th>
            <th>Name</th>
            <th>Next</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $time = new DateTime("now", new DateTimeZone("UTC"));
        foreach ($nextEvents as $event) {
            if ($time < $event->occurrences[0]) {
                $time = $event->occurrences[0];
        ?>
                <tr class="divider">
                    <th colspan="3"></th>
                </tr>
            <?php
            }
            $key = "";
            if (property_exists($event, "manual"))
                $key = $event->manual;
            if (property_exists($event, "id"))
                $key = $event->id;
            ?>
            <tr data-key="<?php echo $key; ?>">
                <td data-id="section"><?php echo $event->section; ?></td>
                <td data-id="name">
                    <a data-id="val" target="_blank" href="<?php echo $event->url; ?>"><?php echo $event->name; ?></a>
                    <?php
                    if (property_exists($event, "manual")) {
                    ?>
                        <a data-id="complete" data-value="<?php echo $event->manual; ?>" href="javascript:void(0)">(Toggle Completion)</a>
                    <?php
                    }
                    ?>
                </td>
                <td data-id="next" utc-convert><?php echo $event->occurrences[0]->format("Y-m-d\TH:i:s\Z"); ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<h3 id="all">All Events</h3>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Next</th>
            <th>Others</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $section = "";
        foreach ($events as $event) {
            if ($section !== $event->section) {
                $section = $event->section;
        ?>
                <tr class="divider">
                    <th colspan="3" data-id="section"><?php echo $section; ?></th>
                </tr>
            <?php
            }
            $key = "";
            if (property_exists($event, "manual"))
                $key = $event->manual;
            if (property_exists($event, "id"))
                $key = $event->id;
            ?>
            <tr data-key="<?php echo $key; ?>">
                <td data-id="name">
                    <a data-id="val" target="_blank" href="<?php echo $event->url; ?>"><?php echo $event->name; ?></a>
                    <?php
                    if (property_exists($event, "manual")) {
                    ?>
                        <a data-id="complete" data-value="<?php echo $event->manual; ?>" href="javascript:void(0)">(Toggle Completion)</a>
                    <?php
                    }
                    ?>
                </td>
                <?php
                if (property_exists($event, "occurrences")) {
                ?>
                    <td data-id="next" utc-convert><?php echo $event->occurrences[0]->format("Y-m-d\TH:i:s\Z"); ?></td>
                    <td data-id="others">
                        <?php
                        for ($x = 1; $x < count($event->occurrences); $x++) {
                        ?>
                            <span utc-convert><?php echo $event->occurrences[$x]->format("Y-m-d\TH:i:s\Z"); ?></span><?php echo ($x < count($event->occurrences) - 1 ? ", " : ""); ?>
                        <?php
                        }
                        ?>
                    </td>
                <?php
                } else {
                ?>
                    <td>No Timer</td>
                    <td>No Timer</td>
                <?php
                }
                ?>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php

$template->returnFooter();
