<?php

require_once('../../config.php');
require($CFG->dirroot.'/local/geldercohortcl/locallib.php');

// Require login.
require_login();

$strtitle = get_string('pluginname', 'local_geldercohortcl');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/geldercohortcl/index.php');

// Require Capability.
require_capability('local/geldercohortcl:synccohorts', $systemcontext);

local_geldercohortcl_cohortsynctask();
