<?php

require_once('../../config.php');
require($CFG->dirroot.'/local/geldercorreo/locallib.php');

// Require login.
require_login();

$strtitle = get_string('pluginname', 'local_geldercorreo');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/geldercorreo/index.php');

// Require Capability.
require_capability('local/geldercorreo:sendmail', $systemcontext);

local_geldercorreo_sendmailtask();
