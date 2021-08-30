<?php

namespace block_mcdpde\models;

/**
 * model used for manage all courses and comptencies
 * for mcdUpdateCompetency task
 *
 */
use core_competency\api;

class coursesModel
{

  public function getUserCourseCompetency()
  {
    global $DB;

    $lastUnixDate = 0;

    $lastUpdate = $DB->get_record('mcdpde_config', array('name' => 'lastupdate'));
    if (!$lastUpdate) {
      mtrace('no hay lastupdate');
      $newUpdate = new \stdClass();
      $newUpdate->name = 'lastupdate';
      $newUpdate->value = 0;
      $DB->insert_record('mcdpde_config', $newUpdate );
      $lastUpdate = $DB->get_record('mcdpde_config', array('name' => 'lastupdate'));
    }
    else {
      $lastUnixDate = $lastUpdate->value;
    }


    $sql = 'SELECT MAX(ag.id) as id,
    ag.userid, MAX(ag.timemodified) as timemodified,
    gi.courseid, cmc.competencyid, ag.grade, gi.gradepass
    FROM
        {assign_grades} ag
        INNER JOIN {grade_items} gi ON ag.assignment = gi.iteminstance
        INNER JOIN {course_modules} cm ON cm.course = gi.courseid aND cm.module = 1 AND cm.instance = ag.assignment
        INNER JOIN {competency_modulecomp} cmc ON cmc.cmid = cm.id
     WHERE ag.timemodified >= '.$lastUnixDate.
     ' GROUP BY cmc.competencyid, gi.courseid, ag.userid, ag.userid, ag.id, ag.grade, gi.gradepass, ag.timemodified';
    // original query WHERE
    // WHERE  ag.grade >= gi.gradepass AND ag.timemodified >= '.$lastUnixDate.
    $records = $DB->get_records_sql($sql);

    foreach ($records as $row) {
      try {
        if ( $row->grade >= $row->gradepass ) {
          mtrace($row->id.' User id:'.$row->userid.' CourseId:'.$row->courseid.' ComptencyId:'.$row->competencyid. ' grade pass');
          api::grade_competency_in_course($row->courseid, $row->userid, $row->competencyid, 2);
        }
        else {
          mtrace($row->id.' User id:'.$row->userid.' CourseId:'.$row->courseid.' ComptencyId:'.$row->competencyid. ' not grade pass');
          api::grade_competency_in_course($row->courseid, $row->userid, $row->competencyid, 1);
        }
      } catch (\coding_exception $e) {
        echo "Excepción de calificación: ".$e->getMessage().".--> con el UserId:".$row->userid."\n";
        echo "Verifique que el usuario $row->userid está matriculado en el curso $row->courseid
        y que tenga el permiso \"moodle/competency:coursecompetencygradable\"\n";
        // Check that the user is enrolled in the course, and is "gradable".
        // throw new coding_exception('The competency may not be rated at this time.');
      } catch (\Exception $e) {
        echo "Excepción de calificación: ".$e->getMessage().".--> con el UserId:".$row->userid."\n";
      }

    }

    $lastUpdate->value = time();
    $DB->update_record('mcdpde_config', $lastUpdate);

    //return $records;
  }
}

?>
