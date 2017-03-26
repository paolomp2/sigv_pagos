<?php

namespace sigc\Http\Containers;

class studentContainer
{
	//Lista de elementos
	private $id = null;
	private $id_md5 = null;
	
	public function __get($property) {
	    if (property_exists($this, $property)) {
	  		return $this->$property;
	    }
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}
}
?>