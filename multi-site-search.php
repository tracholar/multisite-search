<?php
/*
Plugin Name: Multiple Site Search
Description: Multiple Site Search.
Version: 1.0.0
Author: tracholar
Author URI: http://zuoyuan.blog.ustc.edu.cn
License: GPLv2 or later
*/




function search_callback(){
	if(!empty($_GET['t_type'])){
		$options = get_option('mss_options');
		
		if(($i = array_search($_GET['t_type'], $options['name'])) !== False){
			$host = parse_url($options['search_url'][$i], PHP_URL_HOST);
			//filter local search 
			if($host === parse_url(site_url(), PHP_URL_HOST) || in_array($options['search_url'][$i],array('/',''))){
				return;
			}
			header('Location:'. str_replace('{$s}',$_GET['s'], $options['search_url'][$i]));
			die();
		}
		
	}
}

add_action('init', 'search_callback');

function add_options_menu(){
	add_options_page('Multiple site search options','Mulsite search','manage_options','MSS','options_page');
	
}


function options_page(){

	if($_POST && isset($_POST['action']) && $_POST['action'] == 'save'){
		$options = array(
			'id' => $_POST['mss_id'],
			'name' => $_POST['mss_name'],
			'search_url' => $_POST['mss_search_url']
		);
		update_option('mss_options', $options);
	}
	$options = get_option('mss_options');
	//var_dump($options);
	?>
	<h2>Multisite search options</h2>
	<script type="text/template" id="option-template">
	<tr>
					<td>
			name
					</td>
					<td>
		<input type="text" name="mss_name[]" size="40" value="">
					</td>
				</tr>
				
				<tr>
					<td>
		url
					</td>
					<td>
		<input type="text" name="mss_search_url[]" size="80" value="">
					用{$s}代替搜索文本
					</td>
				</tr>
	</script>
	<form action="" method="post">
		<table>
			<tbody>
			<?php 
			for($i=0;$i<count($options['name']);$i++):
			?>
				<tr>
					<td>
			name
					</td>
					<td>
		<input type="text" name="mss_name[]" size="40" value="<?php echo $options['name'][$i] ?>">
					</td>
				</tr>
				
				<tr>
					<td>
		url
					</td>
					<td>
		<input type="text" name="mss_search_url[]" size="80" value="<?php echo $options['search_url'][$i] ?>">
					用{$s}代替搜索文本
					</td>
				</tr>
			<?php 
			endfor;
			?>
				<tr>
					<td>
		<input type="submit" name="action" value="save">
		<input type="button" value="添加" onclick="jQuery(this).parents('tr:first').before(jQuery('#option-template').html())">
					</td>
					<td>
					</td>
				</tr>
	</form>
<?php 

}
add_action('admin_menu', 'add_options_menu');



class MSSWidget extends WP_Widget {
	function MSSWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Multisite search form widget' );
	}

	function widget( $args, $instance ) {
		// Widget output
		?>
		<form id="search-form" name="search_form" action="<?php echo site_url(); ?>" target="_blank" method="get">
							
			<select id="pt1" class="select" name="t_type">
			<?php 
				$options = get_option('mss_options');
				foreach($options['name'] as $name){
					echo '<option value="'.$name.'">'.$name.'</option>';
				}
			?>
				
			</div> 
			<input id="q" class="enter" name="s" placeholder="输入关键词…">
			<input class="sb" type="submit" value="搜索">
		</form>
		
	<?php 
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
		// Output admin widget options form
		
	}
}

function myplugin_register_widgets() {
	register_widget( 'MSSWidget' );
}

add_action( 'widgets_init', 'myplugin_register_widgets' );
?>