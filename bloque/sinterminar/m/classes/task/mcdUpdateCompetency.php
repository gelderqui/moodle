<?php

namespace block_mcdpde\task;

/**
 * Definition of mcdUpdateCompetency scheduled task
 *
 * @category task
 * @package block_mcdpde\task
 *
 * @copyright 2018 Dhaby Xiloj <dhabyx@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
use block_mcdpde\models\coursesModel;

class mcdUpdateCompetency extends \core\task\scheduled_task
{

    /**
     * return name of task for admin panel.
     *
     * @return string name
     */
    public function get_name()
    {
        return get_string('updatecompetency', 'block_mcdpde');
    }

      /**
       * method to execute by cron task.
       */
      public function execute()
      {
          global $CFG;
          mtrace('----------- MCD - UPGRADE COMPETENCY TASK --------------');
          $courseModel = new coursesModel();
          $courseModel->getUserCourseCompetency();
      }
}



?>
