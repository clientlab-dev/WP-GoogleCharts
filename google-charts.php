<?php
/*
Plugin Name:  WP Google Charts
Description: Show google charts with filters by cols
Plugin URI:
Author: Victor Kulyabin
Author URI: v.kulyabin@clientlab.ru
Text Domain: kap
Domain Path: lang
Version: 1.0
*/

define('GCS_PATH', str_replace('/','',plugin_dir_path( __FILE__ )) );
define('GCS_URL', plugin_dir_url( __FILE__ ) );
define('GCS_BASE', plugin_basename(__FILE__) );

//echo GCS_PATH.'\admin.php';

require(GCS_PATH.'\admin.php');

add_action('admin_footer', 'google_charts_init');


function google_charts_init(){
	echo "<script>var GoogleCharts = {GCS_PATH: '".GCS_PATH."',GCS_URL: '".GCS_URL."',GCS_BASE: '".GCS_BASE."'}</script>";
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


class google_chart{
	function the_create_chart($title, $url, $chartType, $filters, $verticalTitle, $horizontalTitle){
		$output = "<div id='".generateRandomString(4)."' class='google-chart-entity' data-table-id='".$url."' data-visualization-type='".$chartType."' data-title='".$title."' data-v-title='".$verticalTitle."' data-h-title='".$horizontalTitle."' data-filters-cols='".$filters."'><div class='chart' ></div></div>";
		return $output;
	}


}

function showChart( $atts ){

	//print_r($atts);
	$title = $atts['title'];
	$url = $atts['url'];
	$chartType = $atts['chart-type'];
	//$chartType = "LineChart";
	$filters = $atts['filters'];
	$verticalTitle = $atts['vertical-title'];
	$horizontalTitle = $atts['horizontal-title'];
	$google_charts = new google_chart();
	return $google_charts->the_create_chart($title, $url, $chartType, $filters, $verticalTitle, $horizontalTitle);
}

//wp_enqueue_script('jquery');
wp_enqueue_script( "chartsloader", "https://www.gstatic.com/charts/loader.js",array(), '1.0',true );
wp_enqueue_script( "chartjs", GCS_URL.'chart.js',array('chartsloader','jquery'), '1.0',true );
add_shortcode('googleChart', 'showChart');
?>
