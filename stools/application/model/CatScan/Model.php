<?php
class CatScan_Model extends Model {

	private $_items = array();
	
	public function __construct() { }

	public function query() {
		$timeStart = $this->getTime();
		$request = SingletonFactory::getInstance( 'Request' );
		
		$url = "http://toolserver.org/~jkroll/CatGraphApi/CatGraphApi.php";
		$url .= "?format=json";
		$url .= "&template=" . $request->getVar( 'flaw' );
		$url .= "&wiki=dewiki";
		$url .= "&ns=0";
		$url .= "&op=list";
		$url .= "&depth=" . $request->getVar( 'depth' );
		$url .= "&cat=" . $request->getVar( 'categories' );
		
		$this->_result = json_decode( file_get_contents( $url ) );
		return $this;
	}

	public function getItems() {
		return $this->_result->items;
	}
	
	function getTime() {
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
    }
	
	public function toSortedArray() {
		$items = $this->_result;
		if (is_object($items)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$items = get_object_vars( $this->_result );
		}
 
		if (is_array($items)) {
			return array_map(__FUNCTION__, $items);
		} else {
			return $items;
		}
	}
}
