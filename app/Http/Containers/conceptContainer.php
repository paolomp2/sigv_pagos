<?php

namespace sigc\Http\Containers;

class conceptContainer
{
	//Lista de elementos
	private $concepts = null;
	private $concept_groups = null;
	//atributos de elementos
	private $id = null;
	private $name = null;
	private $amount = null;
	private $year = null;
	private $fecha_vigencia = null;
	private $fecha_vencimiento = null;
	
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