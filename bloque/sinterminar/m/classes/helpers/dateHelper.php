<?php

namespace block_mcdpde\helpers;

/**
 * Class for create manage intervals of renew competencies
 * in all pages
 */
class dateHelper
{
  public static function geetIntervalString($interval)
  {
    switch ($interval) {
      case 'd':
        return get_string('day','block_mcdpde');
        break;
      case 'm':
        return get_string('month','block_mcdpde');
        break;
      case 'y':
        return get_string('year','block_mcdpde');
        break;
    }
    return null;
  }
}

?>
