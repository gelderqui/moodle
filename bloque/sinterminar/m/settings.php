<?php
// This file is part of mcd_pde block
//
// This plugin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * mcd_pde block for PDE reports.
 *
 * @package    block_mcdpde
 * @copyright  2017 Dhaby Xiloj <dhaby@corplearning.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
if ($ADMIN->fulltree) {
  $settings->add(new admin_setting_heading('block_mcdpde_expiredateheading',
                                         get_string('settings_expiredateh', 'block_mcdpde'),
                                         get_string('settings_expiredatedesch', 'block_mcdpde')));

  $settings->add(new admin_setting_configcolourpicker('block_mcdpde_expiredate',
                                                get_string('settings_expiredate', 'block_mcdpde'),
                                                get_string('settings_expiredatedesc', 'block_mcdpde'),
                                                '#FF0000'));

  $days = array();
  foreach (range(1,31) as $d) {
    $days[$d]=$d;
  }

  $settings->add(new admin_setting_configselect('block_mcdpde_expiredays',
                                                get_string('settings_expiredaysh', 'block_mcdpde'),
                                                get_string('settings_expiredaysdesch', 'block_mcdpde'),
                                                15, $days));




}
