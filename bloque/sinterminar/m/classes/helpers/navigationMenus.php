<?php

namespace block_mcdpde\helpers;

/**
 * Class for create navigation menus for use
 * in all pages
 */
class navigationMenus
{

  // Constant for represent none in select menu
  const CP_NONE = 0;

  // MENU constans for enable each one
  const CP_BOARD = 1;
  const CP_MYBOARD = 2;
  const CP_POP = 3;
  const CP_MEDALS = 4;

  // admin menus
  const CP_ABILITIES = 1;
  const CP_CATEGORIES = 2;

  public static function createAdminMenus($active = self::CP_NONE)
  {
    global $PAGE;

    $context = \context_system::instance();

    if (has_capability('block/mcdpde:allowmanage', $context) || is_siteadmin()) {
      $menuCPGroup = $PAGE->settingsnav->add(get_string('adminmenu', 'block_mcdpde'),
                                            null, \navigation_node::TYPE_CATEGORY, 'CEMPRO');
     //
     // Categories
     $categories = $menuCPGroup->add(get_string('report_categories', 'block_mcdpde'),
                                           new \moodle_url('/blocks/mcdpde/abilities/view.php'),
                                           \navigation_node::TYPE_RESOURCE, null, null,
                                           new \pix_icon('t/preferences', 'icon')
                                         );

      // Abilities
      $abilities = $menuCPGroup->add(get_string('report_abilities', 'block_mcdpde'),
                                            new \moodle_url('/blocks/mcdpde/abilities/abilitiesview.php'),
                                            \navigation_node::TYPE_RESOURCE, null, null,
                                            new \pix_icon('t/preferences', 'icon')
                                          );
      // // Categories
      // $categories = $menuCPGroup->add(get_string('activities', 'block_cempro_plans'),
      //                                       new \moodle_url('/blocks/cempro_plans/activities/view.php'),
      //                                       \navigation_node::TYPE_RESOURCE, null, null,
      //                                       new \pix_icon('t/preferences', 'icon')
      //                                     );

      switch ($active) {
        case self::CP_ABILITIES:
          $abilities->make_active();
          break;
        case self::CP_CATEGORIES:
          $categories->make_active();
          break;
      }
    }
  }

  public static function createPluginMenus($active = self::CP_NONE, $idplan = 0, $idrange = 0)
  {
    global $PAGE, $USER;

    $menuGroup = $PAGE->navigation->add(get_string('report_menu', 'block_mcdpde'),
                                              null, \navigation_node::TYPE_CATEGORY, 'CEMPRO');

    //myboard for plans
    $myboard = $menuGroup->add(get_string('report_myboard', 'block_mcdpde'),
                                          new \moodle_url('/blocks/mcdpde/boards/myboard.php'),
                                          \navigation_node::TYPE_RESOURCE, null, null,
                                          new \pix_icon('i/report', 'icon')
                                        );

    //board for plans
    $board = $menuGroup->add(get_string('report_board', 'block_mcdpde'),
                                          new \moodle_url('/blocks/mcdpde/boards/board.php'),
                                          \navigation_node::TYPE_RESOURCE, null, null,
                                          new \pix_icon('i/report', 'icon')
                                        );
    //popboard for plans
    $popboard = $menuGroup->add(get_string('report_popboard', 'block_mcdpde'),
                                          new \moodle_url('/blocks/mcdpde/boards/popboard.php'),
                                          \navigation_node::TYPE_RESOURCE, null, null,
                                          new \pix_icon('i/report', 'icon')
                                        );
    //medals board for plans
    $medalboard = $menuGroup->add(get_string('report_medals', 'block_mcdpde'),
                                          new \moodle_url('/blocks/mcdpde/boards/medalboard.php'),
                                          \navigation_node::TYPE_RESOURCE, null, null,
                                          new \pix_icon('i/report', 'icon')
                                        );
    //
    //
    //

    // activate menus
    switch ($active) {
      case self::CP_BOARD:
        $board->make_active();
        break;
      case self::CP_MYBOARD:
        $myboard->make_active();
        break;
      case self::CP_POP:
        $popboard->make_active();
        break;
      case self::CP_MEDALS:
        $medalboard->make_active();
        break;
    }
  }
}

?>
