<?php

namespace sigc\Http\Containers;

class configurationContainer
{
	//Lista de elementos
	private $id = null;
	private $year = null;
	private $visible = null;
	private $writeable = null;

	private $configurations = null;
	
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