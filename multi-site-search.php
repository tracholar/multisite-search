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
		switch($_GET['t_type']){
			case 'book':
				
				header('Location:'. str_replace('{$s}',$_GET['s'], $options['search_url']));
				die();
				break;
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
	
	?>
	<h2>Multisite search options</h2>
	<form action="" method="post">
	<input type="hidden" name="mss_id" value="<?php echo $options['id'] ?>">
		<table>
			<tbody>
				<tr>
					<td>
			name
					</td>
					<td>
		<input type="text" name="mss_name" size="40" value="<?php echo $options['name'] ?>">
					</td>
				</tr>
				
				<tr>
					<td>
		url
					</td>
					<td>
		<input type="text" name="mss_search_url" size="80" value="<?php echo $options['search_url'] ?>">
					用{$s}代替搜索文本
					</td>
				</tr>
				
				<tr>
					<td>
		<input type="submit" name="action" value="save">
					</td>
					<td>
					</td>
				</tr>
	</form>
<?php 

}
add_action('admin_menu', 'add_options_menu');
?>