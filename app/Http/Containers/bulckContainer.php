<?php

namespace sigc\Http\Containers;

class bulckContainer
{
	private $id = null;

	//Lista de elementos
	private $num_classrooms = 0;
	private $num_students = 0;
	
	private $classrooms = null;
	private $studentsxclassrooms = null;
	
	private $file_name = null;

	private $num_students_aux = 0;


	
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