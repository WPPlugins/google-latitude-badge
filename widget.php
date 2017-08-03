<?php
/**
 * Plugin Name: Google Latitude Badge Widget
 * Plugin URI: https://code.google.com/p/world-travel-blog
 * Description: This widget will display your Google Latitude location badge on your blog.
 * Version: 1.0
 * Author: Peter Rosanelli
 * Author URI: http://www.worldtravelblog.com
 */

/**
* LICENSE
* This file is part of Google Latitude Badge Widget.
*
* Google Latitude Badge Widget is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
* @package    google-Latitude-badge-widget
* @author     Peter Rosanelli <peter@worldtravelblog.com>
* @copyright  Copyright 2011 Peter Rosanelli
* @license    http://www.gnu.org/licenses/gpl.txt GPL 2.0
* @version    1.0
* @link       https://code.google.com/p/world-travel-blog
*/

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'glatitudebadge_load_widget' );

/**
 * Register the widget.
 */
function glatitudebadge_load_widget() {
	register_widget( 'Google_Latitude_Badge_Widget' );
}

/**
 * Google Latitude Badge Widget class
 */
class Google_Latitude_Badge_Widget extends WP_Widget {
	

    /** constructor */
	function Google_Latitude_Badge_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'google_latitude_badge', 'description' => 'This widget will display your Google Latitude location badge on your blog.' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'google-latitude-badge-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'google-latitude-badge-widget', 'Google Latitude Badge Widget', $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */	
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<iframe src="http://www.google.com/latitude/apps/badge/api?user='.$instance['userid'].'&type=iframe&maptype='.$instance['maptype'].'&'.$instance['zoomlevel'].'" width="'.$instance['width'].'" height="'.$instance['height'].'" frameborder="0"></iframe>';
			
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['userid'] = strip_tags( $new_instance['userid'] );
		$instance['maptype'] = strip_tags($new_instance['maptype'] );
		$instance['zoomlevel'] = strip_tags($new_instance['zoomlevel'] );
		$instance['height'] = strip_tags($new_instance['height'] );
		$instance['width'] = strip_tags($new_instance['width'] );
	
		return $instance;
	}
	
	/** @see WP_Widget::form */
	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'My Current Location',
			'maptype' => 'terrain',
			'zoomlevel' => 'z=7', // region
			'height' => 300,
			'width' => 180
		)); 
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:300px;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'userid' ); ?>">User Id: </label>
			<input id="<?php echo $this->get_field_id( 'userid' ); ?>" name="<?php echo $this->get_field_name( 'userid' ); ?>" value="<?php echo $instance['userid']; ?>" style="width:300px;" /><br/>
			<a href="http://www.google.com/latitude/apps/badge" target="_blank" style="font-size:x-small;">get google latitude user id</a>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'maptype' ); ?>">Map Type:&nbsp;&nbsp;&nbsp;</label>
			<select id="<?php echo $this->get_field_id( 'maptype' ); ?>" name="<?php echo $this->get_field_name( 'maptype' ); ?>">
				<option value="terrain" <?php if($instance['maptype'] == 'terrain') { echo 'selected'; } ?> >Terrain</option>
				<option value="satellite" <?php if($instance['maptype'] == 'satellite') { echo 'selected'; } ?> >Satellite</option>
				<option value="hybrid" <?php if($instance['maptype'] == 'hybrid') { echo 'selected'; } ?> >Hybrid</option>
				<option value="roadmap" <?php if($instance['maptype'] == 'roadmap') { echo 'selected'; } ?> >Roadmap</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'zoomlevel' ); ?>">Zoom Level: </label>
			<select id="<?php echo $this->get_field_id( 'zoomlevel' ); ?>" name="<?php echo $this->get_field_name( 'zoomlevel' ); ?>">
				<option value="" <?php if($instance['zoomlevel'] == '') { echo 'selected'; } ?> >Automatic</option>
				<option value="z=10" <?php if($instance['zoomlevel'] == 'z=10') { echo 'selected'; } ?> >City</option>
				<option value="z=7" <?php if($instance['zoomlevel'] == 'z=7') { echo 'selected'; } ?> >Region</option>
				<option value="z=5" <?php if($instance['zoomlevel'] == 'z=5') { echo 'selected'; } ?> >Country</option>
				<option value="z=3" <?php if($instance['zoomlevel'] == 'z=3') { echo 'selected'; } ?> >Continent</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">Height: </label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" style="width:40px" />	
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">Width:&nbsp;</label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" style="width:40px" />	
		</p>
<?php
		}
}

?>