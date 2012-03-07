<?php
require_once('SVGGraph/SVGGraph.php');
function make_piechart (array $values, array $labels) {



foreach ($values as $k => &$v){
	if ($v == 0) $v = 0.001;
	
	}

if ($labels == NULL) {
	$labels[] = "no article";
	$labels[] = "not linked";
	$labels[] = "linked";
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


}

/*




$values = $_GET["values"];
$title = $_GET["title"];
$language = $_GET["lg"];

$values = explode('*',$values);
if ($language == "de"){
	$labels[] = "Kein Artikel";
	$labels[] = "Nicht verlinkt";
	$labels[] = "Artikel verlinkt";
	$Unterschrift = "Verteilung relevanter Links fuer ".$title ." (".$language.".wikipedia.org)";
	} else {
	$labels[] = "no article";
	$labels[] = "not linked";
	$labels[] = "linked";
	$Unterschrift = "Distribution of relevant links for ".$title ." (".$language.".wikipedia.org)";
}

$summeArray = array_sum($values);

foreach ($values as $k => $v){
		$temp = $v / $summeArray;
		$kreis_wert[$k] = round($temp * 360);
		$proz_wert[$k] = round($temp * 100);
}

$Lizenz = "© Wikimedia Deutschland, CC-BY-SA 3.0";

$myImage = ImageCreateTrueColor(822,526);


$white = ImageColorAllocate ($myImage, 255, 255, 255);
$color1  = ImageColorAllocate ($myImage, 255,0,0);
$color3b = ImageColorAllocate ($myImage, 128,128,0);
$color3 = ImageColorAllocate ($myImage, 255,255,0);
$color5 = ImageColorAllocate ($myImage, 0,128,0);
$black = ImageColorAllocate ($myImage, 0,0,0);
$grey = ImageColorAllocate ($myImage, 144,144,144);

ImageFill($myImage, 0, 0, $white);



ImageFilledArc($myImage, 203, 203, 400, 400, 0, $kreis_wert[0], $color1, IMG_ARC_PIE);


ImageFilledArc($myImage, 203, 203, 400, 400, $kreis_wert[0], $kreis_wert[1] + $kreis_wert[0] , $color3, IMG_ARC_PIE);


ImageFilledArc($myImage, 203, 203, 400, 400, $kreis_wert[1] + $kreis_wert[0], 360 , $color5, IMG_ARC_PIE);

imagesetthickness($myImage, 3);


ImageFilledArc($myImage,  203,  203,  401,  401,   $kreis_wert[1] + $kreis_wert[0], 360, $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);


ImageFilledArc($myImage,  203,  203,  401,  401,  $kreis_wert[0], $kreis_wert[1] + $kreis_wert[0] , $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);



ImageFilledArc($myImage,  203,  203,  401,  401,   0, $kreis_wert[0], $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);





ImageTTFText ($myImage, 24, 0, 441, 60,  $color1, "./LiberationSerif-Regular.ttf", $proz_wert[0] ."%  ".$labels[0] );
ImageTTFText ($myImage, 24, 0, 441, 120,  $color3b, "./LiberationSerif-Regular.ttf", $proz_wert[1] ."%  ".$labels[1] );
ImageTTFText ($myImage, 24, 0, 441, 180,  $color5, "./LiberationSerif-Regular.ttf", $proz_wert[2] ."%  ".$labels[2] );


ImageTTFText ($myImage, 20, 0, 10, 482, $black, "./LiberationSerif-Regular.ttf",  $Unterschrift );
ImageTTFText ($myImage, 12, 0, 3, 520, $black, "./LiberationSerif-Regular.ttf",  $Lizenz );



header ("Content-type: image/png");

Imagepng($myImage);

ImageDestroy($myImage);
	
*/


?>

