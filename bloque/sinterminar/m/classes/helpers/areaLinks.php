<?php

namespace block_mcdpde\helpers;

use block_mcdpde\models\areaModel;

/**
 * Class for manage links for areas to new categoriesid
 */
class areaLinks
{

  public static function createLinks($urlBase, $hiddenId = 0)
  {

    $areaModel = new areaModel();
    $areas = $areaModel->getAllRecords();

    $str= '';
    foreach ($areas as $area) {
      $str .= '[';
      if ($hiddenId != $area->id ) {
        $str .= \html_writer::link(new \moodle_url($urlBase,array('area' => $area->id)),
                                 $area->areaname);
      }
      else {
        $str .= $area->areaname;
      }

      $str .= '] ';
    }
    return $str;
  }
}
