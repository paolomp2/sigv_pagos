<?php

namespace sigc\Http\Containers;

class concept_groupsContainer
{
	//Lista de elementos
	private $concept_groups = null;

	//atributos de elementos
	private $id = null;
	private $name = null;
	private $amount = null;
	
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