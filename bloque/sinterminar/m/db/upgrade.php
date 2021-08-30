<?php

function xmldb_block_mcdpde_upgrade($oldversion)
{
  global $DB;

  $dbman = $DB->get_manager();

  if ($oldversion < 2018012803) {

        // Define table mcdpde_config to be created.
        $table = new xmldb_table('mcdpde_config');

        // Adding fields to table mcdpde_config.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('value', XMLDB_TYPE_CHAR, '100', null, null, null, null);

        // Adding keys to table mcdpde_config.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for mcdpde_config.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // mcdpde savepoint reached.
        upgrade_block_savepoint(true, 2018012803, 'mcdpde');
    }

    //upgrade from here
    $areaVersion = 2018022001;
    if ($oldversion < $areaVersion) {
      // Define table mcdpde_areas to be created.
      $table = new xmldb_table('mcdpde_areas');

      // Adding fields to table mcdpde_areas.
      $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      $table->add_field('areaname', XMLDB_TYPE_CHAR, '100', null, null, null, null);

      // Adding keys to table mcdpde_areas.
      $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

      // Conditionally launch create table for mcdpde_areas.
      if (!$dbman->table_exists($table)) {
         $dbman->create_table($table);
      }

      // Define field id to be added to mcdpde_categories.
      $table = new xmldb_table('mcdpde_categories');
      $field = new xmldb_field('areaid', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'position');

      // Conditionally launch add field areaid.
      if (!$dbman->field_exists($table, $field)) {
          $dbman->add_field($table, $field);
      }
      $key = new xmldb_key('areaid_ref', XMLDB_KEY_FOREIGN, array('areaid'), 'mcdpde_areas', array('id'));

      // Launch add key areaid_ref.
      $dbman->add_key($table, $key);

      // adding new base data
      $area1 = new stdClass();
      $area1->id = 1; $area1->areaname="Restaurante";
      $area2 = new stdClass();
      $area2->id = 2; $area2->areaname="McCafé";
      $area3 = new stdClass();
      $area3->id = 3; $area3->areaname="Centro de Postres";
      $area4 = new stdClass();
      $area4->id = 4; $area4->areaname="Express";
      $area5 = new stdClass();
      $area5->id = 5; $area5->areaname="McCafé Bistro";

      $DB->insert_record('mcdpde_areas',$area1, true);
      $DB->insert_record('mcdpde_areas',$area2, true);
      $DB->insert_record('mcdpde_areas',$area3, true);
      $DB->insert_record('mcdpde_areas',$area4, true);
      $DB->insert_record('mcdpde_areas',$area5, true);

      //$DB->execute("UPDATE {mcdpde_categories} SET areaid=1 WHERE 1");

      // Grupopdc savepoint reached.
      upgrade_block_savepoint(true, $areaVersion, 'mcdpde');
    }

    if ($oldversion < 2019112205) {

        // Define field id to be added to mcdpde_abilities.
        $table = new xmldb_table('mcdpde_abilities');
        $field = new xmldb_field('orden', XMLDB_TYPE_INTEGER, '10', null, null, null, null, null);

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Grupopdc savepoint reached.
        upgrade_block_savepoint(true, 2019112205, 'mcdpde');
    }


    return true;
}

?>
