<?php
/**
 * Definition of local_mcdenroll scheduled tasks.
 *
 * @package    tool_mcdenroll\models
 *
 * @copyright 2017 Dhaby Xiloj <dhabyx@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_mcdenroll\models;
require_once $CFG->dirroot.'/enrol/locallib.php';

use course_enrolment_manager;
use context_course;

/**
 * Class for enroll users to courses.
 */
class enrolUser
{
    /**
     * Enrol users in a course.
     * @param  record $course A course record, obtainded with $DB->get_record
     * @param  integer $userid A number with user ID.
     * @param  record $role   A role record, from moodle role table
     */
    public static function enrolUserInCourse($course, $userid, $role)
    {
      global $PAGE;

      $context = context_course::instance($course->id);
      $manager = new course_enrolment_manager($PAGE,$course);
      $userEnrolments = $manager->get_user_enrolments($userid);
      if (count($userEnrolments)>0){
        //find for manual enrolment
        foreach ($userEnrolments as $enrolment ) {
          if ($enrolment->enrolmentinstance->enrol == 'manual')
            break;
        }
        // user has a manual enrolment, then do noting
        if ($enrolment->enrolmentinstance->enrol == 'manual')
          return;
      }

      $instances = $manager->get_enrolment_instances();

      foreach ($instances as $instance ) {
        if ( $instance->enrol == 'manual' )
          break;
      }

      if ($instance->enrol != 'manual' ) {
        mtrace('No manual enrolment instance has activated for this course: '.$course->fullname);
        return;
      }

      $plugins = $manager->get_enrolment_plugins();

      if (!isset($plugins['manual'])) {
        mtrace('No manual enrolment plugin for this course: '.$course->fullname);
        return;
      }

      $plugin = $plugins['manual'];

      $today = time();

      $plugin->enrol_user($instance, $userid, $role->id, $today, 0);

    }
}
