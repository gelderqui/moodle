<?php
/**
 * mcd local caps.
 *
 * @package   local_mcdenroll
 * @copyright 2017 Dhaby Xiloj <dhabyx@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
  'tool/mcdenroll:managepassword' => array(
      'riskbitmask' => RISK_CONFIG,
      'captype' => 'write',
      'contextlevel' => CONTEXT_SYSTEM,
      'archetypes' => array(
            'manager' => CAP_ALLOW
        ),
  ),
);
