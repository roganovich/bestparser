<?php 
class Builder{
	public $html=array();
	
	function __construct($value) {
          
		$this->html[$value] = new $value ();
		echo "new Builder Class {$value}<br />";
	}
	function getParser($value){
		return $this->html[$value];
	}
}

?>