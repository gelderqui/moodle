<?php
namespace block_mcdpde\tables;

require_once $CFG->libdir.'/tablelib.php';

class competenciesTable extends \table_sql
{
  public $columns;
  public $competency;

  public function __construct($uniqueid)
  {
      parent::__construct($uniqueid);

      $headers = array(
          get_string('competency', 'block_mcdpde'),
          get_string('level', 'block_mcdpde'),
          get_string('medal', 'block_mcdpde'),
          get_string('actions', 'block_mcdpde'),
        );

      $this->columns = array('shortname','levelab', 'medal', 'actions');

      $this->define_columns($this->columns);
      $this->define_headers($headers);
      $this->sortable(true);
  }

  public function setModel(\block_mcdpde\models\mcdpdeModelBase $model)
  {
      $this->set_sql(
              $model->getQuerySelect(),
              $model->getQueryFrom(),
              $model->getQueryWhere(),
              $model->getQueryParams()
              );
      //$this->set_count_sql($model->getCountSQL(),$model->getQueryParams());
  }

  public function setCompetency($id)
  {
    $this->competency = $id;
  }

  public function col_actions($value)
  {
    $params = array('id' => $value->id, 'idability' => $this->competency);
    $editURL = \html_writer::link(new \moodle_url('/blocks/mcdpde/competencies/new.php', $params),
                                  '['.get_string('edit', 'block_mcdpde').']'
                                  );

    $params = array('id' => $value->id, 'ability' => $this->competency);

    $deleteURL =  \html_writer::link(new \moodle_url('/blocks/mcdpde/competencies/delete.php', $params),
                                  '['.get_string('delete', 'block_mcdpde').']'
                                  );
     $str = $editURL. ' '. $deleteURL;
    //$str = $deleteURL;
    return $str;
  }

  public function other_cols($colname, $value)
  {
      if (in_array($colname, $this->columns)) {
          return null;
      }
      return "not yet implement";
  }
}

?>
