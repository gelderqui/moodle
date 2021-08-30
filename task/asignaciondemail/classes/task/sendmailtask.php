<?php
namespace local_geldercorreo\task;

require_once($CFG->dirroot.'/local/geldercorreo/locallib.php');

/**
 * Extend core scheduled task
 */
class sendmailtask extends \core\task\scheduled_task {
    /**
     * Return name of the Task
     * 
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'local_geldercorreo');
    }
    
    /**
     * Perform the task
     */
    public function execute() {
        local_geldercorreo_sendmailtask('auto');
    }
}
