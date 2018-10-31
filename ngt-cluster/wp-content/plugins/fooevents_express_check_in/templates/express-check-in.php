<div id="fooevents-express-check-in-wrapper">
    <div class="fooevents-express-check-in-container ">
        <h1><?php echo __( 'Express Check-in', 'fooevents-express-check-in' ); ?></h1>

        <form id="fooevents-express-check-in-search-form">
            <?php echo $multiday_options; ?>
            <input type="checkbox" id="fooevents-express-check-in-search" name="fooevents-express-check-in-search" class="fooevents-express-check-in-search fooevents-express-check-in-checkbox-option" value="auto-search" checked> Auto Search <input type="checkbox" id="fooevents-express-check-in-auto-check-in" name="fooevents-express-check-in-auto-check-in" class="fooevents-express-check-in-auto-check-in fooevents-express-check-in-checkbox-option" value="auto-check-in"> Auto Check-in<br>
            <input type="text" id="fooevents-express-check-in-value" name="fooevents-express-check-in-value" class="" autocomplete="off" />
            <input type="submit" id="fooevents-express-check-submit" class="button button-primary button-hero" name="fooevents-express-check-submit" value="<?php echo __( 'Search Attendees', 'fooevents-express-check-in' ); ?>" />
        </form>
    </div>
    
    <div id="fooevents-express-check-in-message-wrapper" class=""></div>
    <div id="fooevents-express-check-in-output">

    </div>
</div>
