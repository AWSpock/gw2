<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/_template.php");


$template = new Template();

$template->returnHeader();

$nowUtc = new DateTime("now", new DateTimeZone("UTC"));

$events = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_data/event-timers.json"));
$sections = [];
$nextEvents = [];
$sectionsNext = [];

foreach ($events as $event) {
    if (!in_array($event->section, $sections))
        array_push($sections, $event->section);

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
    return [$a->occurrences[0], $b->section_sort] <=> [$b->occurrences[0], $a->section_sort];
});

foreach ($nextEvents as $event) {
    if (!in_array($event->occurrences[0], $sectionsNext))
        array_push($sectionsNext, $event->occurrences[0]);
}

?>

<h2>Event Timers</h2>

<div class="alert alert-info" id="play-message" style="display:none;">
    <p>You have not played today!</p>
</div>

<ul>
    <li><a href="#next">Next Events</a></li>
    <li><a href="#all">All Events</a>
        <ul>
            <?php
            foreach ($sections as $section) {
            ?>
                <li><a href="#<?php echo $section; ?>"><?php echo $section; ?></a></li>
            <?php
            }
            ?>
        </ul>
    </li>
</ul>

<h3 id="next">Next</h3>

<div class="next-events">
    <?php
    foreach ($sectionsNext as $section) {
    ?>
        <h4 utc-convert><?php echo $section->format("Y-m-d\TH:i:s\Z"); ?></h4>

        <div class="form-group table-next-events">
            <?php
            $es = array_filter($nextEvents, function ($event) use ($section) {
                return $event->occurrences[0] == $section;
            });
            foreach ($es as $event) {
            ?>
                <div
                    <?php
                    if (property_exists($event, "manual"))
                        echo "data-manual='" . $event->manual . "'";
                    if (property_exists($event, "id"))
                        echo "data-key='" . $event->id . "'";
                    ?>>
                    <div data-id="section"><?php echo $event->section; ?></div>
                    <div data-id="name">
                        <a data-id="val" target="_blank" href="<?php echo $event->url; ?>"><?php echo $event->name; ?></a>
                        <?php
                        if (property_exists($event, "manual")) {
                        ?>
                            <div><a data-id="complete" data-value="<?php echo $event->manual; ?>" href="javascript:void(0)">(Toggle Completion)</a></div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    if (property_exists($event, "occurrences")) {
                    ?>
                        <div data-id="next" utc-convert><?php echo $event->occurrences[0]->format("Y-m-d\TH:i:s\Z"); ?></div>
                    <?php
                    } else {
                    ?>
                        <div data-id="next">No Timer</div>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
</div>


<h3 id="all">All Events</h3>

<div class="all-events">
    <?php
    foreach ($sections as $section) {
    ?>
        <h4 id="<?php echo $section; ?>"><?php echo $section; ?></h4>

        <div class="form-group table-all-events">
            <?php
            $es = array_filter($events, function ($event) use ($section) {
                return $event->section == $section;
            });
            foreach ($es as $event) {
            ?>
                <div
                    <?php
                    if (property_exists($event, "manual"))
                        echo "data-manual='" . $event->manual . "'";
                    if (property_exists($event, "id"))
                        echo "data-key='" . $event->id . "'";
                    ?>>
                    <div data-id="name">
                        <a data-id="val" target="_blank" href="<?php echo $event->url; ?>"><?php echo $event->name; ?></a>
                        <?php
                        if (property_exists($event, "manual")) {
                        ?>
                            <div><a data-id="complete" data-value="<?php echo $event->manual; ?>" href="javascript:void(0)">(Toggle Completion)</a></div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    if (property_exists($event, "occurrences")) {
                    ?>
                        <div data-id="next" utc-convert><?php echo $event->occurrences[0]->format("Y-m-d\TH:i:s\Z"); ?></div>
                        <div data-id="others">
                            <?php
                            for ($x = 1; $x < count($event->occurrences); $x++) {
                            ?>
                                <span utc-convert><?php echo $event->occurrences[$x]->format("Y-m-d\TH:i:s\Z"); ?></span><?php echo ($x < count($event->occurrences) - 1 ? ", " : ""); ?>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div data-id="next">No Timer</div>
                        <!-- <div data-id="others">No Timer</div> -->
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    /*$section = "";
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
        }*/
    ?>
</div>

<?php

$template->returnFooter();
