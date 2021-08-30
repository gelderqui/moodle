<?php
/**
* Definition of local_mcd scheduled tasks.
*
* @package    tool_mcdenroll\models
*
* @copyright 2017 Dhaby Xiloj <dhabyx@gmail.com>
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
namespace tool_mcdenroll\models;

/**
* Class for enroll all users in courses
*/
class userModel
{

  private $coursesToEnroll;
  private $puestoIdToEnroll;
  private $puestoProfesor;
  private $puestoIdToEnrollprofesin; //puestos de profesor sin permisos de edicion  - en la tabla lea_empleado

  public function __construct()
  {
    /* Array con los ID de cursos a enrolar
    por ejemplo el curso Áreas de Apoyo tiene el ID=62*/
    $this->coursesToEnroll = array( 62, 42, 23, 65, 63, 64, 83, 102, 122, 123);
    /*Yacky: cursos para gerentes*/
    $this->GerenciaCoursesToEnroll = array( 222, 223, 224, 225, 226, 227, 283, 282, 242, 262);
    /* ID de los puestos a enrolar, obtenidos de la tabla
    LEA_EMPLEADO_MV.COD_PUESTO*/
    //$this->puestoIdToEnroll = array( 60000077, 60000078, 60000079, 60000034, 00024547, 10, 00004789, 00006990, 60000020, 00008373, 60004853, 60000127, 00019421, 60000136, 60004760 );

    
	/*ID de los puestos a enrolar,obtenidos de la tablaLEA_EMPLEADO_MV.COD_PUESTO*/

    //$this->puestoIdToEnrollprofesin = array(00011078, 00021378, 60000055, 83, 50, 00011900, 00011901, 00011079, 00011080, 00011081, 00024426, 00011082 , 00015791 , 60000056, 60000078, 00015363, 60000095 );

    /* CODIGO de perfil de usuario, obtenidos de la tabla
    LEA_ASIGNA_PERFIL_MV.PERFIL */
    $this->puestoProfesor = array('CO', 'GR');

  }


  public function enrolUsers()
  {
    global $CFG, $DB;

    $courses = array();
	
    /*YK:cursos de gerentes*/
    $coursesgerentes = array();

    foreach ($this->coursesToEnroll as $courseid ) {
      $courses[] = $DB->get_record('course',array('id'=>$courseid),'*', MUST_EXIST);
    }

    foreach ($this->GerenciaCoursesToEnroll as $idcourse ) {
      $coursesgerentes[] = $DB->get_record('course',array('id'=>$idcourse),'*', MUST_EXIST);
    }
    $gerentes=$this->getEmpleadosPorPuestoGerentes();
	echo "Cargados los Gerentes";
    $crews = $this->getEmpleadosPorPuestoCrew();
    echo "Cargados los crew";

    $profesorsins = $this->getProfesoresPorpuestoprofesorsin();
    echo "Cargados los profesorsin";

    $roleStudent = $DB->get_record('role', array('shortname' => 'student'), '*', MUST_EXIST);

    $roleProfesorsin = $DB->get_record('role', array('shortname' => 'teacher'), '*', MUST_EXIST);
    //*Gerentes *//
    foreach ($gerentes as $gerente) {
      echo $gerente->id;
      mtrace('User id: '.$gerente->id.', name: '.$gerente->nombre.' '.$gerente->apellido);
      foreach ($coursesgerentes as $courseg) {
        mtrace('    Enrolling to course: '.$courseg->fullname);
        enrolUser::enrolUserInCourse($courseg, $gerente->id, $roleStudent);
      }
    }

    foreach ($crews as $crew) {
      echo $crew->id;
      mtrace('User id: '.$crew->id.', name: '.$crew->nombre.' '.$crew->apellido);
      foreach ($courses as $course) {
        mtrace('    Enrolling to course: '.$course->fullname);
        enrolUser::enrolUserInCourse($course, $crew->id, $roleStudent);
      }
    }
    foreach ($profesorsins as $profesorsin) {
      echo $profesorsin->id;
      mtrace('User id: '.$profesorsin->id.', name: '.$profesorsin->nombre.' '.$profesorsin->apellido);
      foreach ($courses as $course) {
        mtrace('    Enrolling Teacher sin to course: '.$course->fullname);
        enrolUser::enrolUserInCourse($course, $profesorsin->id, $roleProfesorsin);
      }

    }


    $roleTeacher = $DB->get_record('role', array('shortname' => 'teacher'), '*', MUST_EXIST);

    foreach ($this->puestoProfesor as $profesor) {
      //  por puesto
      $profesores = $this->getEmpleadosPorPuestoProfesor($profesor,'P');
      foreach ($profesores as $user) {
        mtrace('User id: '.$user->id.', name: '.$user->nombre.' '.$user->apellido);
        foreach ($courses as $course) {
          mtrace('    Enrolling Teacher to course: '.$course->fullname);
          enrolUser::enrolUserInCourse($course, $crew->id, $roleTeacher);
        }
      }
      // por código
      $profesores = $this->getEmpleadosPorPuestoProfesor($profesor,'C');
      foreach ($profesores as $user) {
        mtrace('User id: '.$user->id.', name: '.$user->nombre.' '.$user->apellido);
        foreach ($courses as $course) {
          mtrace('    Enrolling Teacher to course: '.$course->fullname);
          enrolUser::enrolUserInCourse($course, $crew->id, $roleTeacher);
        }
      }
    }

  }
  /*yk:funcion para traer gerentes */
  private function getEmpleadosPorPuestoGerentes()
  {
    global $DB;

    $sql = "SELECT u.id, u.username, le.NOMBRE, le.APELLIDO
    FROM {user} u
    INNER JOIN LEA_EMPLEADO_MV le ON le.NO_CIA=u.institution AND le.NO_EMPLE=u.idnumber
    WHERE u.deleted = 0 AND le.status_emp='A' AND ((le.cod_puesto in (
      60004690
      ,27356
      ,5865
      ,60000113
      ,15791
      ,60000056
      ,4789
      ,6990
      ,60000020
      ,8373
      ,11078
      ,11900
      ,11901
      ,30034
      ,11079
      ,11080
      ,11081
      ,24426
	  ,60006724
	  ,60006851
	  ,51
	  ,60000055
      ,11082
    ) and le.codigo_pais='SV')
    or
    (le.cod_puesto in (
      60004690
      ,27356
      ,5865
      ,60000113
      ,15791
      ,60000056
      ,4789
      ,6990
      ,60000020
      ,8373
      ,11078
      ,11900
      ,11901
      ,30034
      ,11079
      ,11080
      ,11081
      ,24426
	  ,60006724
	  ,60006851
	  ,60000055
      ,51
      ,11082
    ) and le.codigo_pais='GT')
    or
    (le.cod_puesto in(
      63,
      62,
      99,
      34,
      54,
      56,
      1,
      57,
      83,
      59,
	  60006724,
	  60006851,
	  60000055,
      51,
      64
    )and le.codigo_pais='HN')
    or

    (le.cod_puesto in(
      62,
      3,
      34,
      54,
      56,
      1,
      57,
      58,
      59,
	  60006724,
	  60006851,
	  60000055,
      51,
      64
    )and le.codigo_pais='NI'))";

    $record = $DB->get_records_sql( $sql );
    return $record;
  }
  private function getEmpleadosPorPuestoCrew()
  {
    global $DB;

    $sql = 'SELECT u.id, u.username, e.NOMBRE, e.APELLIDO
    FROM {user} u
    INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
    WHERE u.deleted = 0 AND e.COD_PUESTO IN (60000077,60000078,60000079,60000034,00024547,10,00004789,00006990,60000020,00008373,60004853,60000127,00019421,60000136,60004760,00000,00011078,00021378,60000055,83,50,00011900,00011901,00011079,00011080,00011081,00024426,00011082,00015791,60000056,60000078,00015363,60000095,00,1,100,11,12,13,14,19,2,20,22,23,24,26,27,3,31,33,35,36,37,38,39,4,40,41,42,43,44,45,46,47,48,49,5,51,52,54,55,56,57,58,59,6,60,63,64,65,66,68,7,73,8,85,87,88,9,92,93,94,95,96,97,98,99,00030034)';
    //WHERE u.deleted = 0 AND e.COD_PUESTO IN ('.implode(',' , $this->puestoIdToEnroll ).')';
    $record = $DB->get_records_sql( $sql );
    return $record;
  }
  private function getProfesoresPorpuestoprofesorsin()
  {
    global $DB;

    $sql = 'SELECT u.id, u.username, e.NOMBRE, e.APELLIDO
    FROM {user} u
    INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
    WHERE u.deleted = 0 AND e.COD_PUESTO IN (00011078,00021378,60000055,83,50,00011900,00011901,00011079,00011080,00011081,00024426,00011082,00015791,60000056,00015363,60000095,4,5,6,7,8,9,40,42,1,2,57,59,58,63,64,85,00030034)';

    $record = $DB->get_records_sql( $sql );
    return $record;
  }


  private function getEmpleadosPorPuestoProfesor($perfil, $tipo)
  {
    global $DB;

    $joinTipo = '';

    if ($tipo == 'P') {
      $joinTipo = 'ap.CODIGO = e.COD_PUESTO';
    }
    elseif ($tipo == 'C') {
      $joinTipo = 'ap.CODIGO = e.NO_EMPLE';
    }

    $sql = 'SELECT u.id, e.NOMBRE, e.APELLIDO
    FROM {user} u
    INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
    INNER JOIN LEA_ASIGNA_PERFIL_MV ap ON ap.NO_CIA = e.NO_CIA AND '.$joinTipo.' AND ap.TIPO = \''.$tipo.'\'
    WHERE u.deleted = 0 AND ap.PERFIL LIKE \'%'.$perfil.'%\'';

    $record = $DB->get_records_sql( $sql);
    return $record;
  }


};
