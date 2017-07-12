<?php
/*
Plugin Name: Category Clouds Widget
Plugin URI: http://www.bassett-jones.com/category-clouds-wordpress-widget/
Description: Adds a sidebar widget or shortcode to display selected categories as a tag cloud. 
Author: Hugh Bassett-Jones
Author URI: http://hugh.bassett-jones.com
Version: 2.0

    Based on Category Cloud widget by <a href="http://leekelleher.com/wordpress/plugins/category-cloud-widget/">Lee Kelleher</a>.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
  
*/

class widget_categoryclouds extends WP_Widget
{
	// declares the widget_categoryclouds class
	function widget_categoryclouds(){
		$widget_ops = array('classname' => 'widget_categoryclouds', 'description' => __( "Displays selected categories as a tag cloud") );
		$this->WP_Widget('catcloud', __('Category clouds'), $widget_ops);
	}
	
	// widget output
	function widget($args, $instance){
		extract($args);
	
		echo $before_widget;
		
		// omit title if not specified
		if ($instance['title'] != '')
			echo $before_title . $instance['title'] . $after_title;
		
		// build query
		$query = 'show_option_all=1&style=cloud&show_count=1&use_desc_for_title=0&hierarchical=0';
		$query .= '&order=' . $instance['order'];
		$query .= '&orderby=' . $instance['orderby'];		
		if($instance['min_count'] > 0) { $query .= '&hide_empty=1';}				
		
		// specified categories
		$inc_cats = array(); $exc_cats = array();
		foreach (explode("," ,$instance['cats_inc_exc']) as $spec_cat) {
			 if ($spec_cat < 0) { $exc_cats[] = abs($spec_cat); }
			 elseif ( $spec_cat > 0) { $inc_cats[] = abs($spec_cat); }
		}
		if(count($inc_cats) > 0) { $query .= '&include=' . implode(",", $inc_cats); }
		if(count($exc_cats) > 0) { $query .= '&exclude=' . implode(",", $exc_cats); }
				
		// ensure minimum post count
		$cats = get_categories($query);		
		foreach ($cats as $cat)
		{
			$catlink = get_category_link( $cat->cat_ID );
			$catname = $cat->cat_name;
			$count = $cat->category_count;
			if ($count >= $instance['min_count'])
			{
				$counts{$catname} = $count;
				$catlinks{$catname} = $catlink;
			}
		}
		
		// font size calculation
		$spread = max($counts) - min($counts); 
		if ($spread <= 0) { $spread = 1; };
		$fontspread = $instance['max_size'] - $instance['min_size'];
		$fontstep = $spread / $fontspread;
		if ($fontspread <= 0) { $fontspread = 1; }
		
		echo '<p class="catcloud">';
				
		foreach ($counts as $catname => $count) {
			$catlink = $catlinks{$catname};
			echo "\n<a href=\"$catlink\" title=\"see $count posts in $catname\" style=\"font-size:".
				($instance['min_size'] + ceil($count/$fontstep)).$instance['unit']."\">$catname</a> ";
		}
		
		echo '</p>' . $after_widget;
	}
	
	
	// Creates the edit form for the widget.
	function form($instance){

		//Defaults
		$instance = wp_parse_args( (array) $instance, array('min_size' => 50, 'max_size' => 150, 'unit' => '%', 'orderby' => 'name', 'order' => 'ASC', 'min' => 1, 'exclude'=>'') );
		
		?>
		<p>
			<label><?php echo __('Title:') ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo htmlspecialchars($instance['title']) ?>" />
			</label>
		</p>
		<p>
			<?php echo __('Category font size:') ?><br>
			<label><?php echo __('Min: ') ?><input size="2" id="<?php echo $this->get_field_id('min_size') ?>" name="<?php echo $this->get_field_name('min_size') ?>" type="text" value="<?php echo htmlspecialchars($instance['min_size']) ?>"></label>	
			<label><?php echo __('Max: ') ?><input size="2" id="<?php echo $this->get_field_id('max_size') ?>" name="<?php echo $this->get_field_name('max_size') ?>" type="text" value="<?php echo htmlspecialchars($instance['max_size']) ?>"></label>
			<label><?php echo __('Unit: ') ?>
			<select id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>">		
				<option <?php if ( 'pt' == $instance['unit'] ) echo 'selected="selected"'; ?>>pt</option>
				<option <?php if ( 'px' == $instance['unit'] ) echo 'selected="selected"'; ?>>px</option>
				<option <?php if ( 'em' == $instance['unit'] ) echo 'selected="selected"'; ?>>em</option>
				<option <?php if ( '%' == $instance['unit'] ) echo 'selected="selected"'; ?>>%</option>
			</select>
		</p>		
		<p><?php echo __('Order by: ');	?>
			<label><input class="radio" type="radio" <?php if ( 'count' == $instance['orderby'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('orderby') ?>" id="<?php echo $this->get_field_id('orderby') ?>" value="count">&thinsp;<?php echo __('Count') ?></label>
			<label><input class="radio" type="radio" <?php if ( 'name' == $instance['orderby'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('orderby') ?>" id="<?php echo $this->get_field_id('orderby') ?>" value="name">&thinsp;<?php echo __('Name') ?></label>
		</p>		
		<p><?php echo __('Show by: ') ?>
			<label><input class="radio" type="radio" <?php if ( 'ASC' == $instance['order'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('order') ?>" id="<?php echo $this->get_field_id('order') ?>" value="ASC">&thinsp;<?php echo __('Acending') ?></label>
			<label><input class="radio" type="radio" <?php if ( 'DESC' == $instance['order'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('order') ?>" id="<?php echo $this->get_field_id('order') ?>" value="DESC">&thinsp;<?php echo __('Decending') ?></label>
		</p>
		<p><label for="<?php echo $this->get_field_name('min_count') ?>"><?php echo __('Minimum number of posts:') ?><input size="3" id="<?php echo $this->get_field_id('min_count') ?>" name="<?php echo $this->get_field_name('min_count') ?>" type="text" value="<?php echo htmlspecialchars($instance['min_count']) ?>" /></label></p>		
				
		<p>
			<label>
				<?php echo __('Comma separated category IDs (leave blank for all, to exclude a category use a negative categoryID numbers):') ?>
				<input class="widefat" id="<?php echo $this->get_field_id('cats_inc_exc') ?>" name="<?php echo $this->get_field_name('cats_inc_exc') ?>" type="text" value="<?php echo htmlspecialchars($instance['cats_inc_exc']) ?>" />
			</label>
		</p>
		
	<?php
	}
	
	
	// Saves the widgets settings.
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['min_size'] = ($new_instance['min_size'] != '') ? (int) $new_instance['min_size'] : 50;
		$instance['max_size'] = ($new_instance['max_size'] != '') ? (int) $new_instance['max_size'] : 150;
		$instance['unit'] = ($new_instance['unit'] != '') ? $new_instance['unit'] : '%';
		$instance['orderby'] = ($new_instance['orderby'] != '') ? $new_instance['orderby'] : 'name';
		$instance['order'] = ($new_instance['order'] != '') ? $new_instance['order'] : 'ASC';
		$instance['min_count'] = ($new_instance['min_count'] != '') ? (int) $new_instance['min_count'] : 1;
		$instance['cats_inc_exc'] = strip_tags(stripslashes($new_instance['cats_inc_exc']));		
		
		return $instance;
	}
} // end class
	
// Register widget. Calls 'widgets_init' action after the widget has been registered.
function widget_categoryclouds_init() {
	register_widget('widget_categoryclouds');
}	
add_action('widgets_init', 'widget_categoryclouds_init');

// shortcode for use outside widgets
// TODO: refactor so it doesn't repeat the above
function categoryclouds_func( $atts ) {
	
	// defaults
	extract( shortcode_atts( array(
		'min_size' => 50,
		'max_size' => 150,
		'unit' => '%',
		'orderby' => 'name',
		'order' => 'ASC',
		'min_count' => 1,
		'cats_inc_exc' => '',
	), $atts ) );

	// holder
	$holder = '';
	
	// build query
	$query = 'show_option_all=1&style=cloud&show_count=1&use_desc_for_title=0&hierarchical=0';
	$query .= '&order=' . $order;
	$query .= '&orderby=' . $orderby;		
	if($min_count > 0) { $query .= '&hide_empty=1';}				
	
	// specified categories
	$inc_cats = array(); $exc_cats = array();
	foreach (explode("," ,$cats_inc_exc) as $spec_cat) {
		if ($spec_cat < 0) { $exc_cats[] = abs($spec_cat); }
		elseif ( $spec_cat > 0) { $inc_cats[] = abs($spec_cat); }
	}
	if(count($inc_cats) > 0) { $query .= '&include=' . implode(",", $inc_cats); }
	if(count($exc_cats) > 0) { $query .= '&exclude=' . implode(",", $exc_cats); }
	
	// ensure minimum post count
	$cats = get_categories($query);		
	foreach ($cats as $cat)
	{
		$catlink = get_category_link( $cat->cat_ID );
		$catname = $cat->cat_name;
		$count = $cat->category_count;
		if ($count >= $min_count)
		{
			$counts{$catname} = $count;
			$catlinks{$catname} = $catlink;
		}
	}
		
	// font size calculation
	$spread = max($counts) - min($counts); 
	if ($spread <= 0) { $spread = 1; };
	$fontspread = $max_size - $min_size;
	$fontstep = $spread / $fontspread;
	if ($fontspread <= 0) { $fontspread = 1; }

	$holder .= '<p class="catcloud">';
			
	foreach ($counts as $catname => $count) {
		$catlink = $catlinks{$catname};
		$holder .= '<a href="' . $catlink .'" title="see ' . $count . ' posts in ' . $catname . '" style="font-size:' . ($min_size + ceil($count/$fontstep)) . $unit . '">' . $catname . '</a> ';		
	}
	
	$holder .= '</p>';

	return $holder;
}

add_shortcode( 'categoryclouds', 'categoryclouds_func' );
?>