<?php

namespace block_mcdpde\models;

/**
 * query model for exec complex queries to DB.
 */
class categoriesModel extends mcdpdeModelBase
{
  public function configureAllCategories($areaCode=1)
  {
    $this->querySelect = ' c.* ';
    $this->queryFrom = ' {mcdpde_categories} c ';
    $this->queryWhere = 'c.areaid='.$areaCode;
  }

  public static function getAllCategories($areaCode = 1)
  {
    global $DB;

    return $DB->get_records('mcdpde_categories',array('areaid' => $areaCode ));
  }

  public function saveRecord($category)
  {
    global $DB;
    if (!is_object($category)) {
        debugging("Did you remember to use an stdClass as parameter", DEBUG_DEVELOPER);
        return false;
    }
    unset($category->submitbutton);

    if ($category->id > 0)
    {
        return  $DB->update_record('mcdpde_categories',$category);
    }
    else
    {
        unset($category->id);
        return  $DB->insert_record('mcdpde_categories',$category, true);
    }
  }

  public function getRecord($id)
  {
    global $DB;
    return $DB->get_record('mcdpde_categories', array('id' => $id));
  }

  public function deleteRecord($id)
  {
    global $DB;
    $model = new abilitiesModel();
    $abilities = $model->getAbilitiesByCategories($id);
    foreach ($abilities as $ability) {
        $model->deleteRecord($ability->id);
    }
    return $DB->delete_records('mcdpde_categories', array('id' => $id));
  }
}

?>
