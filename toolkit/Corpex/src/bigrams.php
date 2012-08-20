<?php 
$path_data = "/mnt/user-store/render/corpex/data";
// $path_data = "/home/fekepp/projects/corpex/data";



ini_set( 'memory_limit', '512M' );
$query = $_GET["q"]; // TODO Sanitize, lower case
$lang = $_GET["lang"]; // TODO Sanitize, lower case

if (!file_exists($path_data . "/$lang/bigrams_index")) {
  echo '{ "error" : 1 }';
  exit(0);
}


if ($query === '') {
  $query = '$';
}

function get_input_file( $file_type ) {
  global $lang,$query,$path_data;
    
  $current = '';
  
// echo $path_data . "/" . $lang . "/bigrams_index";

  $lines = file($path_data . "/" . $lang . "/bigrams_index");
  foreach ($lines as $line) {
    $entry = explode( "\t", $line, 2 );
    if ($query >= $entry[0]) {
      $current = trim( $entry[1] );
    }
    else {
      break;
    }
  } 
  return $path_data . "/". $lang . "/". $current . "." . $file_type;
}

$query_terms = explode( ' ', $query );
if (count($query_terms) == 2) {
  foreach ($query_terms as $i => $term) {
    if( $term === '') {
      $query_terms[$i] = '$';
    }
  }

  $lines = file(get_input_file( 'txt' ));
  $all = 0;
  $freq = 0;
  foreach ($lines as $line) {
    $entry = explode( "\t", $line, 3 );
    if ($query_terms[0] === $entry[0]) {
      $all += $entry[2];
      if ($query_terms[1] === $entry[1]) {
        $freq = $entry[2];
      }
    }
    else if ($freq > 0) {
      echo "{ \"freq\" : $freq, \"all\" : $all }";
      exit(0);
    }
  }
}

else {
  $lines = file(get_input_file( 'json' ));
  foreach ($lines as $line) {
    $entry = explode( " ", $line, 2 );
    if ($query === $entry[0]) {
      echo $entry[1];
      exit(0);
    }
    else if ($query < $entry[0]) {
      break;
    }
  }
}
  
echo '{ }';
