<?php

defined('MOODLE_INTERNAL') || die();

$tasks = array(
             array(
                 'classname' => 'local_geldercorreo\task\sendmailtask',
                 'blocking'  => 0,
                 'minute'    => '*',
                 'hour'      => '*',
                 'day'       => '*',
                 'dayofweek' => '*',
                 'month'     => '*'
             )
         );
