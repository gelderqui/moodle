<?php

namespace block_mcdpde\models;

/**
 * query model for exec complex queries to DB.
 */

/**
 * query model for using with area codes from MCD
 */
class areaModel extends mcdpdeModelBase
{

  public function getRecord($id)
  {
    global $DB;
    return $DB->get_record('mcdpde_areas', array('id' => $id));
  }

  public function getAllRecords()
  {
    global $DB;
    return $DB->get_records('mcdpde_areas');
  }
}
