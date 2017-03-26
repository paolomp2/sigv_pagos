<?php

namespace sigc\Http\Containers;
use Auth;
use sigc\Http\Libraries\Breadcrumb;

class generalContainer
{
	private $page_name="<button type='button' class='btn btn-danger'>Configure el nombre 1 de la página</button>";
	private $page_name_2="<button type='button' class='btn btn-danger'>Configure el nombre 2 de la página</button>";

	private $page_description="<button type='button' class='btn btn-danger'>Configure la descripción 1 de la página</button>";
	private $page_description_2="<button type='button' class='btn btn-danger'>Configure la descripción 2 de la página</button>";

	private $msg_add_elements = "No name configurated";

	//USER INFORMATION
		//Nombre de usuario por defecto
	private $username= "No name detected";
		//Rol de usuario por defecto
	private $id_rol=-1;
		//imagen de la imagen por defecto
	private $image="cms/images/img.jpg";

	//GENERAL FLAGS
	private $form=false;
	private $date=false;
	private $table=false; //En caso se use una tabla
	private $relative_path="";
	private $create=false;// En caso se inserte un mnuevo elemento
	private $trash = false;//En caso esté viendo la papelera
	private $select = false;//En caso esté viendo la papelera
	private $add_elements = false;
	private $picture = false;

	//NOMBRE DE LA PAGINA
		//Título de la página
	private $head_name = "Registrar";

	//list_of_elements
	private $concepts = array();
	private $concepts_groups = array();
	private $discounts = array();
	private $interests = array();
	private $schedules = array();
	private $groups = array();
	private $classrooms = array();
	private $students = array();
	private $ubigeos = array();
	private $families_members = array();
	private $relationships = array();
	private $studentsXfamily_members = array();
	private $groups_students = array();
	private $configurations = array();
	private $conceptxstudent = array();

	private $concepts_id = array();
	private $concepts_groups_id = array();
	private $discounts_id = array();
	private $interests_id = array();
	private $schedules_id = array();
	private $groups_id = array();
	private $classrooms_id = array();
	private $students_id = array();
	private $ubigeos_id = array();
	private $families_members_id = array();
	private $relationships_id = array();
	private $studentsXfamily_members_id = array();
	private $configurations_id = array();

	private $entity_to_edit = null;

	//base of url
	private $url_base = "no_link";

	//Specials flag
	//Students
	//Flag to students enrolled
	private $students_enrolled = false;
	private $students_all = false;
	private $students_bulck = false;
	//Flags buttons to add

	private $default_buttons = true;
	private $add_buttons = false;
	//num_studentes
	private $num_students = 0;

	//breadcrumb
	private $breadcrumb = array();	

	public function __construct()
	{
		$this->username = Auth::user()->name;
		$this->id_rol = Auth::user()->id_rol;
		
	}

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

	public function breadcrumb($route){
		$bc = new Breadcrumb($route);
		$this->breadcrumb =  $bc->route_list();
	}
}
?>