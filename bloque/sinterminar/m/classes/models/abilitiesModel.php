<?php

namespace block_mcdpde\models;

/**
 * query model for exec complex queries to DB.
 */
class abilitiesModel extends mcdpdeModelBase
{
  public function getAllAbilitiesSQL($count = false, $areaCode = 1)
  {

    $mcdpdeCategories = $areaCode;

    $areaCodeOperator = "=";

    if( $areaCode == 1 )
    {
        $areaCode="1 or a.id=167 or a.id=161 or a.id=162";
    }

    $sql = 'SELECT a.id, MAX(a.ability) as ability, MAX(c.abilityid) as abilityid,
           MAX(a.intervaldate) as intervaldate, MAX(a.intervaltype) as intervaltype
    FROM {mcdpde_abilities} a INNER JOIN {mcdpde_ability_competency} c
   ON a.id = c.abilityid
   INNER JOIN {mcdpde_categories} cat  ON a.categoriesid = cat.id
    WHERE (cat.areaid = '.$areaCode.') GROUP BY a.id, a.orden ORDER BY a.orden';

    if ($count == true)
      return 'SELECT COUNT(A.id) FROM ('.$sql.') A';
    return $sql;
  }

  public function configureAllAbilities($areaCode = 0)
  {
    $this->querySelect = ' a.*, c.name as categoryname ';
    $this->queryFrom = ' {mcdpde_abilities} a INNER JOIN {mcdpde_categories} c
                          ON a.categoriesid = c.id';
    $this->queryWhere = ' c.areaid='.$areaCode;
  }

  public function getAllAbilities($areaCode =1)
  {
    global $DB;

    return $DB->get_records_sql($this->getAllAbilitiesSQL(false, $areaCode));
  }

  public function countAllAbilities($areaCode = 1)
  {
    global $DB;

    return $DB->count_records_sql($this->getAllAbilitiesSQL(true, $areaCode));
  }

  public function getRecord($id)
  {
    global $DB;

    return $DB->get_record('mcdpde_abilities', array('id' => $id));
  }

  public function saveRecord($ability)
  {
    global $DB;
    if (!is_object($ability)) {
        debugging("Did you remember to use an stdClass as parameter", DEBUG_DEVELOPER);
        return false;
    }
    unset($ability->submitbutton);

    if ($ability->id > 0)
    {
        return  $DB->update_record('mcdpde_abilities',$ability);
    }
    else
    {
        unset($ability->id);
        return  $DB->insert_record('mcdpde_abilities',$ability, true);
    }

  }

  public function getAbilitiesByCategories($id)
  {
    global $DB;

    return $DB->get_records('mcdpde_abilities', array('categoriesid' => $id));
  }

  public function deleteRecord($id)
  {
    global $DB;
    $DB->delete_records('mcdpde_ability_competency', array('abilityid' => $id));
    return $DB->delete_records('mcdpde_abilities', array('id' => $id));
  }

}

?>
