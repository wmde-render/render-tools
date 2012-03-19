<?php

$values = $_GET["values"];
$title = $_GET["title"];
$language = $_GET["lang"];

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

$myImage = ImageCreate(411,243);

$white = ImageColorAllocate ($myImage, 255, 255, 255);
$color1  = ImageColorAllocate ($myImage, 255,0,0);
$color3b = ImageColorAllocate ($myImage, 128,128,0);
$color3 = ImageColorAllocate ($myImage, 255,255,0);
$color5 = ImageColorAllocate ($myImage, 0,128,0);
$black = ImageColorAllocate ($myImage, 0,0,0);
$grey = ImageColorAllocate ($myImage, 144,144,144);

ImageTTFText ($myImage, 12, 0, 221, 30,  $color1, "./LiberationSerif-Regular.ttf", $proz_wert[0] ."%  ".$labels[0] );
ImageTTFText ($myImage, 12, 0, 221, 60,  $color3b, "./LiberationSerif-Regular.ttf", $proz_wert[1] ."%  ".$labels[1] );
ImageTTFText ($myImage, 12, 0, 221, 90,  $color5, "./LiberationSerif-Regular.ttf", $proz_wert[2] ."%  ".$labels[2] );


ImageTTFText ($myImage, 10, 0, 10, 241, $black, "./LiberationSerif-Regular.ttf",  $Unterschrift );
ImageTTFText ($myImage, 7, 0, 3, 221, $black, "./LiberationSerif-Regular.ttf",  $Lizenz );



ImageFilledArc($myImage, 103, 103, 200, 200, 0, $kreis_wert[0], $color1, IMG_ARC_PIE);
ImageFilledArc($myImage,  103,  103,  201,  201,   0, $kreis_wert[0], $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

ImageFilledArc($myImage, 103, 103, 200, 200, $kreis_wert[0], $kreis_wert[1] + $kreis_wert[0] , $color3, IMG_ARC_PIE);
ImageFilledArc($myImage,  103,  103,  201,  201,  $kreis_wert[0], $kreis_wert[1] + $kreis_wert[0] , $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

ImageFilledArc($myImage, 103, 103, 200, 200, $kreis_wert[1] + $kreis_wert[0], 360 , $color5, IMG_ARC_PIE);
ImageFilledArc($myImage,  103,  103,  201,  201,   $kreis_wert[1] + $kreis_wert[0], 360, $black, IMG_ARC_EDGED|IMG_ARC_NOFILL);

header ("Content-type: image/jpg");
Imagejpeg($myImage);

ImageDestroy($myImage);
	


?>

