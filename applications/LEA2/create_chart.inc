<?php

function create_piechart_3pieces(array $labels, array $values){


$summeArray = array_sum($values);

foreach ($values as $k => $v){
		$temp = $v / $summeArray;
		$kreis_wert[$k] = round($temp * 360);
		$proz_wert[$k] = round($temp * 100);
}


$myImage = ImageCreate(411,211);

$white = ImageColorAllocate ($myImage, 255, 255, 255);
$color1  = ImageColorAllocate ($myImage, 155,0,50);
$color3 = ImageColorAllocate ($myImage, 134,31,167);
$color5 = ImageColorAllocate ($myImage, 19,55,197);
$black = ImageColorAllocate ($myImage, 0,0,0);
$grey = ImageColorAllocate ($myImage, 144,144,144);

ImageString($myImage, 4, 211, 30, $labels[0], $color1 );
ImageString($myImage, 4, 211, 60, $labels[1], $color3 );
ImageString($myImage, 4, 211, 90, $labels[2], $color5 );

ImageFilledArc($myImage, 100, 100, 200, 200, 0, $kreis_wert[0], $color1, IMG_ARC_PIE);
ImageFilledArc($myImage,  100,  100,  201,  201,   0, $kreis_wert[0], $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

ImageFilledArc($myImage, 100, 100, 200, 200, $kreis_wert[0], $kreis_wert[1] , $color3, IMG_ARC_PIE);
ImageFilledArc($myImage,  100,  100,  201,  201,  $kreis_wert[0], $kreis_wert[1] , $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

ImageFilledArc($myImage, 100, 100, 200, 200, $kreis_wert[1], 360 , $color5, IMG_ARC_PIE);
ImageFilledArc($myImage,  100,  100,  201,  201,   $kreis_wert[1], 360, $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

header ("Content-type: image/png");
ImagePNG($myImage);

ImageDestroy($myImage);
	
	
}

function create_hor_chart(array $labels, array $values, array $colors = NULL ){

if (!isset($colors)) {
	foreach ($labels as $k => $v){
		$colors[$k] = "darkred";
	}
}

$normal_auf = array_sum($values);

$html_table = "\n<table border=\"0\" >";

foreach ($values as $k => $wert){
$norm_wert =  $wert / $normal_auf ;
$norm_wert = $norm_wert * 100; 
$norm_wert = (integer) $norm_wert;
$doppelter_norm_wert = $norm_wert;
if ($norm_wert == 0) $doppelter_norm_wert = 1;
$gegenwert = 100 - $doppelter_norm_wert;
$html_table .= "\n<tr>";
$html_table .= "<td>".$labels[$k]."</td>";
$html_table .= "<td valign=\"middle\">";
$html_table .= "\n<table id=\"bar\" border=\"0\">";
$html_table .= "<tr>";
$html_table .= "<td bgcolor=\"".$colors[$k]."\"><img src=\"leer.png\" border=\"0\" width=\"".$doppelter_norm_wert."px\" height=\"1\"/>&nbsp;</td>";
$html_table .= "<td><small>".$norm_wert."%</small></td>";
$html_table .= "<td bgcolor=\"white\"><img src=\"leer.png\" border=\"0\" width=\"".$gegenwert."px\" height=\"1\"/></td>";
$html_table .= "</tr>";
$html_table .= "</table>";
$html_table .= "</td>";
$html_table .= "</tr>";
}
$html_table .= "</table>\n"; 

return $html_table;

}

?>
