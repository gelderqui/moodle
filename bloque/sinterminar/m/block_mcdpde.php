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


class block_mcdpde extends block_list
{
    protected $contentgenerated = false;

    public function init()
    {
        $this->blockname = get_class($this);
        $this->title = get_string('pluginname', 'block_mcdpde');
    }

    public function get_content()
    {
        global $CFG, $OUTPUT,  $COURSE, $USER;

        if (isset($this->config)) {
            $config = $this->config;
        } else {
            $config = get_config('block_mcdpde');
        }

        if ($this->contentgenerated === true) {
            return $this->content;
        }

        $context = context_system::instance();

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();

        $menuItems = array();

        $reportMenu = html_writer::tag('span',
            get_string('report_menu', 'block_mcdpde'),
            array('class' => 'tree_item branch')
        );

        $this->content->items[] = $reportMenu;
        $this->content->icons[] = $OUTPUT->pix_icon('i/navigationitem', 'icon');

        // report MyBoard
        $menuItems[] = html_writer::tag('div',
        html_writer::link($CFG->wwwroot.'/blocks/mcdpde/boards/myboard.php',
        $OUTPUT->pix_icon('i/navigationitem', 'icon').
        html_writer::span(get_string('report_myboard', 'block_mcdpde'), 'item-content-wrap')
          ),
          array('class' => 'tree_item hasicon')
        );

        // report Board
        $menuItems[] = html_writer::tag('div',
          html_writer::link($CFG->wwwroot.'/blocks/mcdpde/boards/board.php',
            $OUTPUT->pix_icon('i/navigationitem', 'icon').
            html_writer::span(get_string('report_board', 'block_mcdpde'), 'item-content-wrap')
          ),
          array('class' => 'tree_item hasicon')
        );

        // report POP Board
        $menuItems[] = html_writer::tag('div',
            html_writer::link($CFG->wwwroot.'/blocks/mcdpde/boards/popboard.php',
                $OUTPUT->pix_icon('i/navigationitem', 'icon').
                html_writer::span(get_string('report_popboard', 'block_mcdpde'), 'item-content-wrap')
              ),
              array('class' => 'tree_item hasicon')
        );

        // report Medals
        $menuItems[] = html_writer::tag('div',
            html_writer::link($CFG->wwwroot.'/blocks/mcdpde/boards/medalboard.php',
                $OUTPUT->pix_icon('i/navigationitem', 'icon').
                html_writer::span(get_string('report_medals', 'block_mcdpde'), 'item-content-wrap')
              ),
              array('class' => 'tree_item hasicon')
        );

        $this->content->items[] = html_writer::alist($menuItems, array('class' => 'block_tree'));
        $this->content->icons[] = null; // the list do not implements icon

        //admin menus
        if (has_capability('block/mcdpde:allowmanage', $context) || is_siteadmin()) {
            $adminMenu = html_writer::tag('span',
            get_string('adminmenu', 'block_mcdpde'),
              array('class' => 'tree_item branch')
            );

            $this->content->items[] = $adminMenu;
            $this->content->icons[] = $OUTPUT->pix_icon('i/navigationitem', 'icon');

            // Categories
            $adminItems[] = html_writer::tag('div',
                html_writer::link($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php',
                    $OUTPUT->pix_icon('i/navigationitem', 'icon').
                    html_writer::span(get_string('report_categories', 'block_mcdpde'), 'item-content-wrap')
                  ),
                  array('class' => 'tree_item hasicon')
            );

            // Abilities
            $adminItems[] = html_writer::tag('div',
                html_writer::link($CFG->wwwroot.'/blocks/mcdpde/abilities/abilitiesview.php',
                    $OUTPUT->pix_icon('i/navigationitem', 'icon').
                    html_writer::span(get_string('report_abilities', 'block_mcdpde'), 'item-content-wrap')
                  ),
                  array('class' => 'tree_item hasicon')
            );

            $this->content->items[] = html_writer::alist($adminItems, array('class' => 'block_tree'));
            $this->content->icons[] = null; // the list do not implements icon
        }

        $this->contentgenerated = true;

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats()
    {
        return array('all' => true,
                     'blocks' => true,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => true,
                     'mod-quiz' => false, );
    }

    public function instance_allow_multiple()
    {
        return false;
    }

    public function instance_allow_config()
    {
        return false;
    }

    public function has_config()
    {
        return true;
    }

    public function cron()
    {
        return true;
    }
}
