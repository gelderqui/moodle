<?php
namespace local_geldercohortcl\task;

require_once($CFG->dirroot.'/local/geldercohortcl/locallib.php');

/**
 * Extend core scheduled task
 */
class cohortsynctask extends \core\task\scheduled_task {
    /**
     * Return name of the Task
     * 
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'local_geldercohortcl');
    }
    
    /**
     * Perform the task
     */
    public function execute() {
        local_geldercohortcl_cohortsynctask('auto');
    }
}
