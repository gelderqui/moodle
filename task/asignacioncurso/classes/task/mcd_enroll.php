<?php
/**
 * Definition of mcd_enroll scheduled tasks.
 *
 * @category  task
 * @package    tool_mcdenroll\task
 *
 * @copyright 2016 Dhaby Xiloj <dhabyx@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_mcdenroll\task;

use tool_mcdenroll\models\userModel;

class mcd_enroll extends \core\task\scheduled_task
{
    /**
     * return name of task for admin panel.
     *
     * @return string name
     */
    public function get_name()
    {
        return get_string('cronenroll', 'tool_mcdenroll');
    }

    /**
     * method to execute by cron task.
     */
    public function execute()
    {
        global $CFG;
        mtrace('----------- MCD - ENROL TASK --------------');
        $userModel = new userModel();
        $userModel->enrolUsers();

    }
}
