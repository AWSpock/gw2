<?php

class PriceCompare
{
    public function build($list)
    {
        for ($x = 0; $x < count($list); $x++) {
            $item = $list[$x];
?>
            <div data-item-id="<?php echo $item->id; ?>" data-quantity="<?php echo $item->amount; ?>">
                <div class="form-group"><span data-id="name"></span></div>
                <div class="form-group"><a data-id="gw2tp" target="_blank">GW2TP</a></div>
                <?php if ($item->wiki != "") { ?>
                    <div class="form-group"><a href="https://wiki.guildwars2.com/wiki/<?php echo $item->wiki; ?>" target="_blank">Wiki</a></div>
                <?php } ?>
                <img class="form-group" />
                <div class="form-group"><label>Quantity</label>: <span data-id="Quantity"><?php echo $item->amount; ?></span></div>
                <div class="inline">
                    <!-- <div class="form-group"><label>Buy Now</label><span data-id="BuyNow"></span></div> -->
                    <?php
                    if (property_exists($item, "cost")) {
                    ?>
                        <div class="form-group"><label>Cost</label><span data-id="Cost"><?php echo $item->cost; ?></span></div>
                    <?php
                    } else {
                    ?>
                        <div class="form-group"><label>Sell Now</label><span data-id="SellNow"></span></div>
                    <?php
                    }
                    ?>
                    <div class="form-group"><label>Total Compare</label><span data-id="TotalCompare"></span></div>
                </div>

            </div>
<?php
        }
    }
}
