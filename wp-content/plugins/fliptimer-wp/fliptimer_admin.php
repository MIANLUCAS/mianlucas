<?php
	$task = isset( $_REQUEST[ "task" ] ) ? $_REQUEST[ "task" ] : null;

	switch ( strtolower( $task ) ) {
		case "update":
			FlipTimerGlobals::updateOptions();
			break;
	}
	
	$fliptimer_options = FlipTimerGlobals::getOptions();
?>

<div id="fliptimer_general">
    <h2><i class="icon-cog"></i> <?php esc_html_e( "Flip Timer", FLIPTIMER_TEXTDOMAIN ); ?></h2>
    
	<form id="fliptimer_form" action="?page=<?php echo $plugin_page; ?>" method="post">
		<input type="hidden" name="task" value="update" />
	
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "General", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>						
			
			<div class="block first">
			
				<div class="field">	
					<label for="date"><?php esc_html_e( "Date", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="date" name="date" class="regular-text date" type="text" value="<?php echo ( $fliptimer_options[ "date" ] ? $fliptimer_options[ "date" ] : date( "Y/m/d H:i:s", strtotime( '+1 month' ) ) ); ?>" />	
				</div>
				
				<div class="field">
					<label for="timeZone"><?php esc_html_e( "Time Zone", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="timeZone" name="timeZone" class="small-text" type="text" value="<?php echo $fliptimer_options[ "timezone" ]; ?>" data-val="0" />
				</div>
				
				<div class="field clearfix">
					<label for="past"><?php esc_html_e( "Past", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="past" name="past" value="<?php echo $fliptimer_options[ "past" ]; ?>" class="iphone_checkboxes" data-val="false"
					<?php if ($fliptimer_options["past"]=="true") {echo 'checked="checked"';} ?> />
				</div>
				
				<div class="field">
					<label for="dayTextNumber"><?php esc_html_e( "Day Text Number", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="dayTextNumber" name="dayTextNumber" class="small-text" type="text" value="<?php echo $fliptimer_options[ "daytextnumber" ]; ?>" data-val="2" />
					<span class="unit"><?php esc_html_e( "Number / auto", FLIPTIMER_TEXTDOMAIN ); ?></span>
				</div>
				
			</div>			
			
		</div>
		
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "Show/Hide Timers", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>			
			
			<div class="block">
			
				<div class="field clearfix">
					<label for="showDay"><?php esc_html_e( "Show Days", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="showDay" name="showDay" value="<?php echo $fliptimer_options[ "showday" ]; ?>" class="iphone_checkboxes" data-val="true"
					<?php if ( $fliptimer_options[ "showday" ] == "true" ) { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="showHour"><?php esc_html_e( "Show Hours", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="showHour" name="showHour" value="<?php echo $fliptimer_options[ "showhour" ]; ?>" class="iphone_checkboxes" data-val="true"
					<?php if ( $fliptimer_options[ "showhour" ] == "true" ) { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="showMinute"><?php esc_html_e( "Show Minutes", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="showMinute" name="showMinute" value="<?php echo $fliptimer_options[ "showminute" ]; ?>" class="iphone_checkboxes" data-val="true"
					<?php if ( $fliptimer_options[ "showminute" ] == "true") { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="showSecond"><?php esc_html_e( "Show Seconds", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="showSecond" name="showSecond" value="<?php echo $fliptimer_options[ "showsecond" ]; ?>" class="iphone_checkboxes" data-val="true"
					<?php if ( $fliptimer_options[ "showsecond" ] == "true") { echo 'checked="checked"'; } ?> />
				</div>
				
			</div>
			
		</div>
		
		<div class="clear"></div>   
		
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "Texts", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>						
			
			<div class="block">
				
				<div class="field">
					<label for="dayText"><?php esc_html_e( "Days Text", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="dayText" name="dayText" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "daytext" ]; ?>" data-val="Days" />
				</div>
				
				<div class="field">
					<label for="hourText"><?php esc_html_e( "Hours Text", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="hourText" name="hourText" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "hourtext" ]; ?>" data-val="Hours" />
				</div>	
				
				<div class="field">
					<label for="minuteText"><?php esc_html_e( "Minutes Text", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="minuteText" name="minuteText" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "minutetext" ]; ?>" data-val="Minutes" />
				</div>	
				
				<div class="field">
					<label for="secondText"><?php esc_html_e( "Seconds Text", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="secondText" name="secondText" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "secondtext" ]; ?>" data-val="Seconds" />
				</div>		
				
			</div>			
			
		</div>
		
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "Flip style", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>						
			
			<div class="block">
				
				<div class="field">
					<label for="bgColor"><?php esc_html_e( "Background Color", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="bgColor" name="bgColor" class="medium-text color_picker" type="text" value="<?php echo $fliptimer_options[ "bgcolor" ]; ?>" data-val="#333333" />
					<div id="bgColor_bg" class="colorpicker_bg"
						onclick="jQuery('#bgColor').click()"
						<?php if ( $fliptimer_options[ "bgcolor" ] != "" ) { ?>
							style="background-color:<?php echo $fliptimer_options[ "bgcolor" ]; ?>"
						<?php } ?>
					>&nbsp;</div>
				</div>
				
				<div class="field">
					<label for="dividerColor"><?php esc_html_e( "Divider Color", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="dividerColor" name="dividerColor" class="medium-text color_picker" type="text" value="<?php echo $fliptimer_options[ "dividercolor" ]; ?>" data-val="#000000" />
					<div id="dividerColor_bg" class="colorpicker_bg"
						onclick="jQuery('#dividerColor').click()"
						<?php if ( $fliptimer_options[ "dividercolor" ] != "" ) { ?>
							style="background-color:<?php echo $fliptimer_options[ "dividercolor" ]; ?>"
						<?php } ?>
					>&nbsp;</div>
				</div>
				
				<div class="field">
					<label for="digitColor"><?php esc_html_e( "Digit Color", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="digitColor" name="digitColor" class="medium-text color_picker" type="text" value="<?php echo $fliptimer_options[ "digitcolor" ]; ?>" data-val="#ffffff" />
					<div id="digitColor_bg" class="colorpicker_bg"
						onclick="jQuery('#digitColor').click()"
						<?php if ( $fliptimer_options[ "digitcolor" ] != "" ) { ?>
							style="background-color:<?php echo $fliptimer_options[ "digitcolor" ]; ?>"
						<?php } ?>
					>&nbsp;</div>
				</div>
				
				<div class="field">
					<label for="textColor"><?php esc_html_e( "Text Color", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="textColor" name="textColor" class="medium-text color_picker" type="text" value="<?php echo $fliptimer_options["textcolor"]; ?>" data-val="#666666" />
					<div id="textColor_bg" class="colorpicker_bg"
						onclick="jQuery('#textColor').click()"
						<?php if ( $fliptimer_options[ "textcolor" ] != "" ) { ?>
							style="background-color:<?php echo $fliptimer_options[ "textcolor" ]; ?>"
						<?php } ?>
					>&nbsp;</div>
				</div>
				
			</div>			
			
		</div>
		
		<div class="clear"></div> 
		
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "Miscellaneous", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>						
			
			<div class="block">
				
				<div class="field">
					<label for="borderRadius"><?php esc_html_e( "Border Radius", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="borderRadius" name="borderRadius" class="small-text" type="text" value="<?php echo $fliptimer_options[ "borderradius" ]; ?>" data-val="6" />
					<span class="unit">px</span>
				</div>
				
				<div class="field clearfix">
					<label for="boxShadow"><?php esc_html_e( "Box Shadow", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="boxShadow" name="boxShadow" value="<?php echo $fliptimer_options[ "boxshadow" ]; ?>" class="iphone_checkboxes" data-val="true"
					<?php if ( $fliptimer_options[ "boxshadow" ] == "true" ) { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="currentDate"><?php esc_html_e( "Use Server Time", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="currentDate" name="currentDate" value="<?php echo $fliptimer_options[ "currentdate" ]; ?>" class="iphone_checkboxes" data-val="false"
					<?php if ( $fliptimer_options[ "currentdate" ] == "true" ) { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="multiColor"><?php esc_html_e( "Multicolor", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="multiColor" name="multiColor" value="<?php echo $fliptimer_options[ "multicolor" ]; ?>" class="iphone_checkboxes" data-val="false"
					<?php if ( $fliptimer_options[ "multicolor" ] == "true") { echo 'checked="checked"'; } ?> />
				</div>
				
				<div class="field clearfix">
					<label for="autoReset"><?php esc_html_e( "Auto Reset", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input type="checkbox" id="autoReset" name="autoReset" value="<?php echo $fliptimer_options[ "autoreset" ]; ?>" class="iphone_checkboxes" data-val="false"
					<?php if ( $fliptimer_options[ "autoreset" ] == "true" ) {echo 'checked="checked"';} ?> />
				</div>
				
				<div class="field">
					<label for="resetInterval"><?php esc_html_e( "Reset Interval", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="resetInterval" name="resetInterval" class="small-text" type="text" value="<?php echo $fliptimer_options[ "resetinterval" ]; ?>" data-val="86400" />
					<span class="unit">seconds</span>
				</div>
				
			</div>			
			
		</div>
		
		<div class="postbox unite-postbox">
			<h3 class="box-closed">
				<span><?php esc_html_e( "Expire", FLIPTIMER_TEXTDOMAIN ); ?></span>
			</h3>						
			
			<div class="block">
				
				<div class="field">
					<label for="expireType"><?php esc_html_e( "Expire Type", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<select id="expireType" name="expireType" class="regular-text" data-val="message">
						<option value="message" 	<?php echo ( $fliptimer_options[ "expiretype" ] == "message" 	? 'selected="selected"' : '' ); ?>>Message</option>
						<option value="hide"		<?php echo ( $fliptimer_options[ "expiretype" ] == "hide" 	? 'selected="selected"' : '' ); ?>>Hide</option>
						<option value="redirect"	<?php echo ( $fliptimer_options[ "expiretype" ] == "redirect" ? 'selected="selected"' : '' ); ?>>Redirect</option>
					</select>
				</div>
				
				<div class="field expire-type expire-message">
					<label for="message"><?php esc_html_e( "Message Text", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="message" name="message" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "message" ]; ?>" data-val="<?php esc_html_e( "Sorry, you are too late!", FLIPTIMER_TEXTDOMAIN ); ?>" />
				</div>
				
				<div class="field hidden clearfix expire-type expire-redirect">
					<label for="redirect"><?php esc_html_e( "Redirect Url", FLIPTIMER_TEXTDOMAIN ); ?></label>
					<input id="redirect" name="redirect" class="regular-text" type="text" value="<?php echo $fliptimer_options[ "redirect" ]; ?>" data-val="" />
				</div>
				
			</div>			
			
		</div>
		
		<div id="theshortcode">
			<p><strong><?php esc_html_e( "Generated Shortcode", FLIPTIMER_TEXTDOMAIN ); ?></strong></p>
			<textarea class="regular-text"></textarea>
			<div class="clear"></div>
		</div>
		
		<div class="buttons">
			
			<a class="button-primary btn-green fliptimer_save">
				<i class="icon-cog"></i> <?php esc_html_e( "Save Changes", FLIPTIMER_TEXTDOMAIN ); ?>
			</a>			
			
			<a class="button-primary btn-blue fliptimer_generate">
				<i class="icon-pencil"></i> <?php esc_html_e( "Generate Shortcode", FLIPTIMER_TEXTDOMAIN ); ?>
			</a>			
			
			<a class="button-primary btn-yellow fliptimer_cancel" href="?page=<?php echo $plugin_page; ?>">
				<i class="icon-cancel"></i> <?php esc_html_e( "Cancel", FLIPTIMER_TEXTDOMAIN ); ?>
			</a>
		
		</div>
	
	</form>
    
</div>