<?php

defined('MOODLE_INTERNAL') || die();

$tasks = array(
             array(
                 'classname' => 'local_geldercohortcl\task\cohortsynctask',
                 'blocking'  => 0,
                 'minute'    => '15',
                 'hour'      => '0',
                 'day'       => '*',
                 'dayofweek' => '*',
                 'month'     => '*'
             )
         );
