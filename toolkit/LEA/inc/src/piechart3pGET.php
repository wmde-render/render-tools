<?php
require_once('SVGGraph/SVGGraph.php');

$values = $_GET["values"];
$values = explode('*',$values);


foreach ($values as $k => &$v){
	if ($v == 0) $v = 0.001;
	
	}


$labels = $_GET["labels"];
$labels = explode('*',$labels);


if ($labels == NULL) {
	$labels[] = "no article";
	$labels[] = "not linked";
	$labels[] = "linked";
}


foreach ($labels as $k => &$v){
	$v = str_replace('_',' ',$v);
	}




$settings = array(
  'back_colour' => '#fff',  'stroke_colour' => '#000',
  'back_stroke_width' => 0, 'back_stroke_colour' => '#eee',
  'back_image' => NULL,
  'axis_colour' => '#333',  'axis_overlap' => 2,
  'axis_font' => 'Georgia', 'axis_font_size' => 10,
  'grid_colour' => '#fff',  'label_colour' => '#000',
  'pad_right' => 5,        'pad_left' => 5,
  'show_labels' => true,    'show_label_amount' => true,
  'label_font' => 'Arial','label_font_size' => '15',
  'label_position' => 0.5, 
  'show_tooltips' => false,
  'label_fade_in_speed' => 25, 'label_fade_out_speed' => 5,
  'sort' => false
);
 
$labelvalues = array($labels[0] => $values[0], $labels[1] => $values[1], $labels[2] => $values[2]);
 
$colours = array('#ff0000','#ffff00','#008000');
 
$graph = new SVGGraph(250, 250, $settings);
$graph->colours = $colours;
 
$graph->Values($labelvalues);

$graph->Render('PieGraph');


?>
