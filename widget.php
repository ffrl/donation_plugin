<?php
/*
Plugin Name: Freifunk Rheinland e.V. Spendenstatus
Description: Spendenstatus Widget für FFRL
Version: 0.1
Author: Kai "SkaveRat" de Haan
Author URI: http://skaverat.net
Plugin URI: http://freifunk-rheinland.net
*/
class FFRL_Donationstatus extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'FFRL_Donationstatus',
			'FFRL_Donationstatus',
			array('description' => __('A widget to show donation status for ', 'text_domain'),) // Args
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$percentage = $instance['currentStatus'] / $instance['donateGoal'];

		echo $before_widget;
		echo '<div id="ffrlDonation">';
		echo '<h3>' . $title . '</h3>';
		echo '<h4>Status</h4>';
		echo $this->getPercentageImage($instance, $percentage);

		echo '<div class="infoText">';
		echo '<ul>';
		echo '<li>Spendenziel: '.number_format($instance['donateGoal'],0,',','\'') . '&euro;</li>';
		echo '<li>Aktueller Stand: '.number_format($instance['currentStatus'],2,',','\'') . '&euro;</li>';
		echo '</ul>';

		echo '<p>'.$instance['text'].'</p>';
		echo '<a href="'.$instance['detailsUrl'].'">Mehr Info</a>';

		echo '</div>';

		echo '</div>';
		echo $after_widget;
	}

	public function getPercentageImage($instance, $percentage)
	{
		return '<div class="statusImage background">
			<div class="statusImage foreground" style="height:' . floor(181 * $percentage) . 'px;"></div>
			<span>'.number_format($percentage*100,1).'%</span>
		</div>';
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$title = __('Titel', 'text_domain');
		if (isset($instance['title']))
			$title = $instance['title'];
		?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			   name="<?php echo $this->get_field_name('title'); ?>" type="text"
			   value="<?php echo esc_attr($title); ?>"/>
	</p>

	<?php


		$detailUrl = __('URL to detailpage', 'text_domain');
		if (isset($instance['detailsUrl']))
			$detailUrl = $instance['detailsUrl'];
		?>
	<p>
		<label for="<?php echo $this->get_field_id('detailsUrl'); ?>"><?php _e('Details URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('detailsUrl'); ?>"
			   name="<?php echo $this->get_field_name('detailsUrl'); ?>" type="text"
			   value="<?php echo esc_attr($detailUrl); ?>"/>
	</p>

	<?php
		$donateGoal = 10000;
		if (isset($instance['donateGoal']))
			$donateGoal = $instance['donateGoal'];
		?>
	<p>
		<label for="<?php echo $this->get_field_id('donateGoal'); ?>"><?php _e('Donation goal:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('donateGoal'); ?>"
			   name="<?php echo $this->get_field_name('donateGoal'); ?>" type="text"
			   value="<?php echo esc_attr($donateGoal); ?>"/>
	</p>
	<?php

		$currentStatus = 10000;
		if (isset($instance['currentStatus']))
			$currentStatus = $instance['currentStatus'];
		?>
	<p>
		<label for="<?php echo $this->get_field_id('currentStatus'); ?>"><?php _e('Current status:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('currentStatus'); ?>"
			   name="<?php echo $this->get_field_name('currentStatus'); ?>" type="text"
			   value="<?php echo esc_attr($currentStatus); ?>"/>
	</p>
	<?php


		$text = "";
		if (isset($instance['text']))
			$text = $instance['text'];
		?>
	<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Short text:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>"
			   name="<?php echo $this->get_field_name('text'); ?>" type="text"
			   value="<?php echo esc_attr($text); ?>"/>
	</p>
	<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['detailsUrl']		= strip_tags($new_instance['detailsUrl']);
		$instance['donateGoal'] 	= (float) $new_instance['donateGoal'];
		$instance['currentStatus']	= (float) $new_instance['currentStatus'];
		$instance['text']			= strip_tags($new_instance['text']);
		$instance['title']			= strip_tags($new_instance['title']);

		return $instance;
	}
}

function getImageUrl($imagename) {
	return plugins_url($imagename, __FILE__);
}

function ffrl_registerWidgets() {
	register_widget('FFRL_Donationstatus');
}

function ffrl_addStylesheet() {
	wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
	wp_enqueue_style( 'prefix-style' );
}

add_action( 'widgets_init', 'ffrl_registerWidgets' );
add_action( 'wp_enqueue_scripts', 'ffrl_addStylesheet' );


