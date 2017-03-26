<?php

namespace sigc\Http\Containers;

class discountContainer
{
	private $id = null;
	private $id_md5 = null;

	private $name = null;
	private $descripion = null;


	//Lista de elementos
	private $discounts = null;
	
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