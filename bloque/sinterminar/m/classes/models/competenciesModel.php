<?php

namespace block_mcdpde\models;

/**
 * query model for exec complex queries to DB.
 */
class competenciesModel extends mcdpdeModelBase
{

  public function configureAllCompetencies($idAbility)
  {
    //select ac.id, c.shortname
    //from mdl_mcdpde_ability_competency ac
    //INNER JOIN mdl_competency c ON ac.competencyid = c.id
    //where ac.abilityid = :ability
    $this->querySelect = ' ac.id, c.shortname, ac.levelab, ac.medal ';
    $this->queryFrom = ' {mcdpde_ability_competency} ac INNER JOIN {competency} c
                          ON ac.competencyid = c.id';
    $this->queryWhere = ' ac.abilityid = :ability ';
    $this->queryParams['ability'] = $idAbility;
  }

  public static function getCategoryMedalInfo()
  {
    global $DB;

    $sql = "SELECT ac.id, ac.abilityid, ac.competencyid, ac.medal, a.categoriesid
      FROM {mcdpde_ability_competency} ac
      INNER JOIN {mcdpde_abilities} a ON ac.abilityid = a.id
      INNER JOIN {mcdpde_categories} c ON a.categoriesid = c.id AND c.areaid = 1
      WHERE ac.medal IS NOT NULL";

    return $DB->get_records_sql($sql);
  }

  public static function countCompetencies($idAbility)
  {
    global $DB;

    return $DB->count_records('mcdpde_ability_competency', array('abilityid' => $idAbility));
  }

  public static function getCompetenciesAvalaible()
  {
    global $DB;
    $sql = "SELECT * FROM {competency}
         WHERE id NOT IN ( SELECT competencyid FROM {mcdpde_ability_competency} ) ORDER BY shortname";
    // $sql = "SELECT * FROM {competency} c
    // WHERE 1=1 ORDER BY shortname";
    return $DB->get_records_sql($sql);
  }

  public function getCompetencyRecord($id)
  {
    global $DB;
    $sql = "SELECT * FROM {competency} WHERE id = ?";
    return $DB->get_record_sql($sql, array($id));
  }

  public function getRecord($id)
  {
    global $DB;

    return $DB->get_record('mcdpde_ability_competency', array('id' => $id));
  }

  public function saveRecord($ability)
  {
    global $DB;
    if (!is_object($ability)) {
        debugging("Did you remember to use an stdClass as parameter", DEBUG_DEVELOPER);
        return false;
    }
    unset($ability->submitbutton);

    if ($ability->medal == 'N' ) {
      $ability->medal = NULL;
    }

    if ($ability->id > 0)
    {
        return  $DB->update_record('mcdpde_ability_competency',$ability);
    }
    else
    {
        unset($ability->id);
        return  $DB->insert_record('mcdpde_ability_competency',$ability, true);
    }

  }

  public function deleteRecord($id)
  {
    global $DB;

    return $DB->delete_records('mcdpde_ability_competency', array('id' => $id));
  }

  public function getUserCompetencies($userid, $competencyid)
  {
    global $DB;

    $sql="SELECT uct.id AS id, uct.grade, uct.timemodified, ac.levelab
    FROM {competency_usercompcourse} uct
      INNER JOIN {mcdpde_ability_competency} ac ON uct.competencyid = ac.competencyid
      INNER JOIN {mcdpde_abilities} ab ON ab.id = ac.abilityid
      WHERE uct.competencyid = ac.competencyid
        AND uct.userid = $userid AND ab.id = $competencyid
      ORDER BY ac.levelab DESC";

    return $DB->get_records_sql($sql);
  }

}

?>
