<?php

namespace block_mcdpde\helpers;

use block_mcdpde\models\competenciesModel;

/**
 * Class for determinate level AB strings
 * in all pages
 */
class levelabHelper
{
  /**
   * Get level information only
   * @param  int $userid    user id
   * @param  int $abilityid ability id
   * @param  int $date      if 0 return A or B, if 1 then return date
   * @return string         level A or B
   */
  public static function getLevelAB($abstring, $abtime, $abgrade, $date = 0)
  {


    $result = "";
    $gradeA = 0;
    $gradeB = 0;
    $dateA = NULL;
    $dateB = NULL;

    $competencies = explode(',', $abstring);
    $dates = explode(',', $abtime);
    $grades = explode(',', $abgrade);

    // var_dump($competencies);
    // var_dump($dates);
    // var_dump($grades[0]==0);
    // exit;

    if ((count($competencies) != count($dates))
        || (count($competencies) != count($grades))) {
          if ($date == 0) {
            $result = '';
          }
          else {
            $result = NULL;
          }
          return $result;
        }

    for ($i=0; $i < count($competencies); $i++) {
      if ($competencies[$i] == "B"
        && $grades[$i] >= 1) {
        $gradeB = $grades[$i];
        $dateB = $dates[$i];
      }
      if ($competencies[$i] == "A" && $grades[$i] >= 1) {
        $gradeA = $grades[$i];
        $dateA = $dates[$i];
      }
    }
    //test first with B. A is used when one
    switch ($gradeB) {
      case 0:
        // competency only with A
        if ($gradeA == 2) {
          if ($date == 0) {
            $result = 'A';
          }
          else {
            $result = $dateA;
          }
        }
        break;
      case 1:
        // B is not passed, then A is not passed too
        if ($date == 0) {
          $result = '';
        }
        else {
          $result = NULL;
        }
        break;
      case 2:
        // B is passed
        if ($gradeA == 2) {
          if ($date == 0) {
            $result = 'A';
          }
          else {
            $result = $dateA;
          }
        } else {
          if ($date == 0) {
            $result = 'B';
          }
          else {
            $result = $dateB;
          }
        }
        break;
    }

    return $result;
  }
}

?>
