<?php
namespace block_mcdpde\tables;

require_once $CFG->libdir.'/tablelib.php';

use block_mcdpde\helpers\dateHelper;

class abilitiesTable extends \table_sql
{
  public $columns;

  private $areaCode;

  public function __construct($uniqueid, $areaCode = 1)
  {
      parent::__construct($uniqueid);

      $headers = array(
          get_string('ability', 'block_mcdpde'),
          get_string('interval', 'block_mcdpde'),
          get_string('category', 'block_mcdpde'),
          get_string('actions', 'block_mcdpde')
        );

      $this->columns = array('ability', 'intervaldate', 'categoryname', 'actions');

      $this->define_columns($this->columns);
      $this->define_headers($headers);
      $this->sortable(true, 'position');
      $this->areaCode = $areaCode;
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

  public function col_actions($value)
  {
    $params = array('id' => $value->id, 'areaid' => $this->areaCode);
    $editURL = \html_writer::link(new \moodle_url('/blocks/mcdpde/abilities/newability.php', $params),
                                  '['.get_string('edit', 'block_mcdpde').']'
                                  );

    $deleteURL =  \html_writer::link(new \moodle_url('/blocks/mcdpde/abilities/deleteability.php', $params),
                                  '['.get_string('delete', 'block_mcdpde').']'
                                  );
    $competencies = \html_writer::link(new \moodle_url('/blocks/mcdpde/competencies/view.php', $params),
                                  '['.get_string('competencies', 'block_mcdpde').']'
                                  );
    $str = $editURL.' '.$deleteURL. ' '. $competencies;
    return $str;
  }

  public function col_intervaldate($values)
  {
    return $values->intervaldate . ' ' . dateHelper::geetIntervalString($values->intervaltype);
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
