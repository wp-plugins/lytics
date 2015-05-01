<div class="wrap">
<div id="icon-lytics" class="icon32"></div>
<h2><?php _e('Lytics Plugin Settings', 'lytics');?></h2>
</h2>
<form method="post" action="options.php">
<?php settings_fields( 'lytics_account' ); ?>
<?php do_settings_sections(  'lytics_account' ); ?>
<?php submit_button(); ?>
</form>
</div>
