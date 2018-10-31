<div class="item item-flex-nogrow item-conditional" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("exactonly", __("Show exact matches only?", "ajax-search-lite"),
        $sd['exactonly']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect('exact_match_location', "..and match fields against the search phrase",
        array(
            'selects' => array(
                array('option' => 'Anywhere', 'value' => 'anywhere'),
                array('option' => 'Starting with phrase', 'value' => 'start'),
                array('option' => 'Ending with phrase', 'value' => 'end')
            ),
            'value' => $sd['exact_match_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("keyword_logic", __("Keyword (phrase) logic?", "ajax-search-lite"), array(
        'selects'=>array(
            array("option" => "OR", "value" => "OR"),
            array("option" => "AND", "value" => "AND")
        ),
        'value'=>$sd['keyword_logic']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg">This determines if the result should match either of the entered phrases (OR logic) or all of the entered phrases (AND logic).</div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("trigger_on_facet_change", __("Trigger search on facet change?", "ajax-search-lite"),
        $sd['trigger_on_facet_change']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><?php echo __("Will trigger a search when the user clicks on a checkbox on the front-end.", "ajax-search-lite"); ?></p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("triggerontype", __("Trigger search when typing?", "ajax-search-lite"),
        $sd['triggerontype']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("charcount", __("Minimal character count to trigger search", "ajax-search-lite"),
        $sd['charcount'], array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("maxresults", __("Max. results", "ajax-search-lite"), $sd['maxresults'], array(array("func" => "ctype_digit", "op" => "eq", "val" => true)));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("redirect_click_to", __("Action when clicking <strong>the magnifier</strong> icon", "ajax-search-lite"),
        array(
            'selects' => array(
                array("option" => __("Trigger live search", "ajax-search-lite"), "value" => "ajax_search"),
                array("option" => __("Redirec to: Results page", "ajax-search-lite"), "value" => "results_page"),
                array("option" => __("Redirec to: Woocommerce results page", "ajax-search-lite"), "value" => "woo_results_page"),
                array("option" => __("Redirec to: First matching result", "ajax-search-lite"), "value" => "first_result"),
                array("option" => __("Redirec to: Custom URL", "ajax-search-lite"), "value" => "custom_url"),
                array("option" => __("Do nothing", "ajax-search-lite"), "value" => "nothing")
            ),
            'value' => $sd['redirect_click_to']
        ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("click_action_location", " location: ",
        array(
            'selects' => array(
                array('option' => 'Use same tab', 'value' => 'same'),
                array('option' => 'Open new tab', 'value' => 'new')
            ),
            'value' => $sd['click_action_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("redirect_enter_to", __("Action when pressing <strong>the return</strong> key", "ajax-search-lite"),
        array(
            'selects' => array(
                array("option" => __("Trigger live search", "ajax-search-lite"), "value" => "ajax_search"),
                array("option" => __("Redirec to: Results page", "ajax-search-lite"), "value" => "results_page"),
                array("option" => __("Redirec to: Woocommerce results page", "ajax-search-lite"), "value" => "woo_results_page"),
                array("option" => __("Redirec to: First matching result", "ajax-search-lite"), "value" => "first_result"),
                array("option" => __("Redirec to: Custom URL", "ajax-search-lite"), "value" => "custom_url"),
                array("option" => __("Do nothing", "ajax-search-lite"), "value" => "nothing")
            ),
            'value' => $sd['redirect_enter_to']
        ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("return_action_location", " location: ",
        array(
            'selects' => array(
                array('option' => 'Use same tab', 'value' => 'same'),
                array('option' => 'Open new tab', 'value' => 'new')
            ),
            'value' => $sd['return_action_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("custom_redirect_url", __("Custom redirect URL", "ajax-search-lite"), $sd['custom_redirect_url']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">You can use the <string>asl_redirect_url</string> filter to add more variables.</p>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("override_default_results", __("Override the default WordPress search results?", "ajax-search-lite"),
        $sd['override_default_results']);
    $params[$o->getName()] = $o->getData();
    ?>
    <?php
    $o = new wpdreamsCustomSelect("override_method", " method ", array(
        "selects" =>array(
            array("option" => "Post", "value" => "post"),
            array("option" => "Get", "value" => "get")
        ),
        "value" => $sd['override_method']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;"><?php echo __("Might not work with some Themes.", "ajax-search-lite"); ?></p>
</div>