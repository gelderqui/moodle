<?php
/**
 * Definition of local_walmartenroll scheduled tasks.
 *
 * @package    tool_mcdenroll
 *
 * @copyright 2017 Dhaby Xiloj <dhabyx@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$tasks = array(
    // enroll new users
    array(
        'classname' => '\tool_mcdenroll\task\mcd_enroll',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),
);
