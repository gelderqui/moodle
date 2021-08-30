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

  public function __construct()
  {
    /* Array con los ID de cursos a enrolar
      por ejemplo el curso Áreas de Apoyo tiene el ID=62*/
    $this->coursesToEnroll = array( 62, 42, 23, 65, 63, 64 );
    /* ID de los puestos a enrolar, obtenidos de la tabla
      LEA_EMPLEADO_MV.COD_PUESTO*/
    $this->puestoIdToEnroll = array(60000077, 60000034, 10 );

    /* CODIGO de perfil de usuario, obtenidos de la tabla
      LEA_ASIGNA_PERFIL_MV.PERFIL */
    $this->puestoProfesor = array('CO', 'GR');

  }


  public function enrolUsers()
  {
    global $CFG, $DB;

    $courses = array();

    foreach ($this->coursesToEnroll as $courseid ) {
      $courses[] = $DB->get_record('course',array('id'=>$courseid),'*', MUST_EXIST);
    }

    $crews = $this->getEmpleadosPorPuestoCrew();

    $roleStudent = $DB->get_record('role', array('shortname' => 'student'), '*', MUST_EXIST);

    foreach ($crews as $crew) {
      mtrace('User id: '.$crew->id.', name: '.$crew->nombre.' '.$crew->apellido);
      foreach ($courses as $course) {
        mtrace('    Enrolling to course: '.$course->fullname);
        enrolUser::enrolUserInCourse($course, $crew->id, $roleStudent);
      }

    }

    $roleTeacher = $DB->get_record('role', array('shortname' => 'teacher'), '*', MUST_EXIST);

    foreach ($this->puestoProfesor as $profesor) {
      // por puesto
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

  private function getEmpleadosPorPuestoCrew()
  {
    global $DB;

    $sql = 'SELECT u.id, u.username, e.NOMBRE, e.APELLIDO
      FROM {user} u
	       INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
        WHERE u.deleted = 0 AND e.COD_PUESTO IN ('.implode(',' , $this->puestoIdToEnroll ).')';

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
