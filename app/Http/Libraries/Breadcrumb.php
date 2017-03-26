<?php

namespace sigc\Http\Libraries;

class BreadCrumb{

	private $array_links = array();
	private $url_base = "no_base_breadcrumb";
	private $name_base = "no_base_name";
	function __construct($key){

		$aList_root = array();
		$bAddOneToIndex = false;

		$aList_root['href'] = '/';
		$aList_root['label'] = '<div class="breadcrumb_index fa fa-home"></div>';
		$aList_root['current'] = false;
		$this->array_links[0] = $aList_root;
		$aListElementsToProcess = explode(".", $key);
		
		for ($i=0; $i < count($aListElementsToProcess) ; $i++) { 
			if ($bAddOneToIndex) {
				$bAddOneToIndex = false;

				if ($i==count($aListElementsToProcess)-1) {
					$cElementBreadCrumb['href'] = "#";
					$cElementBreadCrumb['current'] = true;
				}else{
					$cElementBreadCrumb['current'] = false;
				}
				$this->array_links[$i]=$cElementBreadCrumb;
				continue;
			}

			$cElementBreadCrumb = array();
			$sElement = $aListElementsToProcess[$i];
			switch ($sElement) {
				//payment
				case 'show_students':
					$this->url_base = $cElementBreadCrumb['href'] ='/showListStudents';					
					$cElementBreadCrumb['label'] ='Pagos: Seleccionar Alumno';
					break;
				case 'getDebtsList':
					$i++;
					$id_d5_student = $aListElementsToProcess[$i];
					$cElementBreadCrumb['href'] ='/getDebsList/'.$id_d5_student;					
					$cElementBreadCrumb['label'] ='Ingresar monto';
					break;
				//end payment
				case 'all_students':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/all/all';					
					$cElementBreadCrumb['label'] ='Lista de alumnos registrados';
					break;

				case 'bulcked_students':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/bulcked/bulcked';					
					$cElementBreadCrumb['label'] ='Lista de alumnos importados';
					break;

				case 'enrolling_fast':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/enrolling_fast/enrolling_fast';					
					$cElementBreadCrumb['label'] ='Matrícula rápida';
					break;
				
				case 'add_student':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/add_student/add_student';					
					$cElementBreadCrumb['label'] ='Selección de alumnos';
					break;

				case 'add_student_store':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/enrolling_fast/store';					
					$cElementBreadCrumb['label'] ='Resultados de Matrícula';
					break;
					
				//Concepts
				case 'concepts':
					$this->url_base = $cElementBreadCrumb['href'] ='/concepts/';
					$this->name_base = "Conceptos";
					$cElementBreadCrumb['label'] ='Lista de Conceptos';
					break;

				case 'discounts':
					$this->url_base = $cElementBreadCrumb['href'] ='/discounts/';
					$this->name_base = "Descuentos";
					$cElementBreadCrumb['label'] ='Lista de Descuentos';
					break;

				case 'interests':
					$this->url_base = $cElementBreadCrumb['href'] ='/'.$sElement.'/';
					$this->name_base = "Intereses";
					$cElementBreadCrumb['label'] ='Lista de Intereses';
					break;

				case 'schedules':
					$this->url_base = $cElementBreadCrumb['href'] ='/'.$sElement.'/';
					$this->name_base = "Cronogramas";
					$cElementBreadCrumb['label'] ='Lista de Cronogramas';
					break;

				case 'classrooms':
					$this->url_base = $cElementBreadCrumb['href'] ='/'.$sElement.'/';
					$this->name_base = "Aulas";
					$cElementBreadCrumb['label'] ='Lista de Aulas';
					break;

				case 'students':
					$this->url_base = $cElementBreadCrumb['href'] ='/students/';
					$this->name_base = "Estudiantes";
					$cElementBreadCrumb['label'] ='Lista de Estudiantes Matriculados';
					break;
				
				case 'concepts_groups':
					$this->url_base = $cElementBreadCrumb['href']='/concepts_groups/';
					$this->name_base = "Grupo de Conceptos";
					$cElementBreadCrumb['label']='Lista de Grupos de Conceptos';
					break;

				case 'family_members':
					$this->url_base = $cElementBreadCrumb['href'] ='/family_members/';
					$this->name_base = "Familiares";
					$cElementBreadCrumb['label'] ='Lista de Fammiliares';
					break;

				case 'groups':
					$this->url_base = $cElementBreadCrumb['href'] ='/'.$sElement.'/';
					$this->name_base = "Grupos";
					$cElementBreadCrumb['label'] ='Lista de grupos';
					break;

				case 'create':
					$cElementBreadCrumb['href']= $this->url_base.'create/';
					$cElementBreadCrumb['label']='Crear '.$this->name_base;
					break;

				case 'edit':
					$cElementBreadCrumb['href']= $this->url_base.'edit/';
					$cElementBreadCrumb['label']='Editar: '.$this->name_base.' '.$aListElementsToProcess[$i+1];
					$i++;
					break;

				case 'trash':
					$cElementBreadCrumb['href']= $this->url_base.'trash/trash';
					$cElementBreadCrumb['label']='Papelera de '.$this->name_base;
					break;

				case 'add':
					$cElementBreadCrumb['href']= $this->url_base.'add';
					$cElementBreadCrumb['label']='Relacionar alumnos';
					break;

				case 'list_groups':

					$cElementBreadCrumb['href']= $this->url_base.$aListElementsToProcess[$i+1].'/add';					
					if($this->name_base == 'Conceptos')
						$cElementBreadCrumb['label']='Lista de Grupos de estudiantes';
					if($this->name_base == 'Familiares')
						$cElementBreadCrumb['label']='Lista de estudiantes de Familiar';
					if($this->name_base == 'Grupos')
						$cElementBreadCrumb['label']='Lista de alumnos en un Grupo';
					if($this->name_base == 'Aulas')
						$cElementBreadCrumb['label']='Lista de alumnos matriculados en aula';
					$bAddOneToIndex = true;
					break;

				case 'add_pre':
					$cElementBreadCrumb['href']= $this->url_base.$aListElementsToProcess[$i+1].'/add_elements';
					$cElementBreadCrumb['label']='Tipo';
					$i++;
					break;

				case 'add_store':
					$cElementBreadCrumb['href']= $this->url_base.$aListElementsToProcess[$i+1].'/add_elements';
					$cElementBreadCrumb['label']='Agregar alumnos';
					$i++;
					break;

				default:
					$cElementBreadCrumb['href']='/';
					$cElementBreadCrumb['label']='ERROR breadcrumb.php';
					break;
			}
			if ($i==count($aListElementsToProcess)-1) {
				$cElementBreadCrumb['href'] = "#";
				$cElementBreadCrumb['current'] = true;
			}else{
				$cElementBreadCrumb['current'] = false;
			}
			
			$this->array_links[$i+1] = $cElementBreadCrumb;
		}
	}

	function route_list()
	{
		return $this->array_links;
	}

}

?>