<?php
namespace block_mcdpde\tables;

use block_mcdpde\models\abilitiesModel;
use block_mcdpde\helpers\levelabHelper;

require_once $CFG->libdir.'/tablelib.php';

class boardPopTable extends \table_sql
{
    public $columns;

    private $colIds;

    public $abilities;

    private $abilitiesDateInfo;

    public function __construct($uniqueid, $download, $areaCode = 1)
    {
        parent::__construct($uniqueid);

        $headers = array(
          get_string('usercode', 'block_mcdpde'),
          // get_string('country', 'block_mcdpde'),
          // get_string('consultant', 'block_mcdpde'),
          // get_string('restaurant', 'block_mcdpde'),
          get_string('name','block_mcdpde'));

        $this->columns = array('code',
        // 'country', 'consultor', 'nombre_rest',
        'name');
        $this->colIds = array();

        $this->abilitiesDateInfo = array();

        $abilitiesModel = new abilitiesModel();

        $this->abilities = $abilitiesModel->getAllAbilities($areaCode);
        foreach ($this->abilities as $id => $ability) {
          if ( $download == '' ){
            $headers[]='<div class="col_rotated">'.$ability->ability.'</div>';
          } else {
            $headers[]=$ability->ability;
          }
          $this->columns[]='col'.$id;
          $this->colIds[]=$id;

          $adi = new \stdClass();
          $adi->intervaldate = $ability->intervaldate;
          $adi->intervaltype = $ability->intervaltype;
          $this->abilitiesDateInfo[$id] = $adi;
        }

        $this->define_columns($this->columns);
        $this->define_headers($headers);
        $this->sortable(false);
    }

    public function setModel(\block_mcdpde\models\QueryModel $model)
    {
        $this->set_sql(
                $model->getQuerySelect(),
                $model->getQueryFrom(),
                $model->getQueryWhere(),
                $model->getQueryParams()
                );
        $this->set_count_sql($model->getCountSQL(),$model->getQueryParams());
    }

    private function addEmptyCols($data)
    {
      foreach ($this->colIds as $id) {
        if ( ! property_exists($data, 'col'.$id)) {
          $colname = 'col'.$id;
          $data->$colname = null;
        }
      }
      return $data;
    }

    /**
     * Here is the format for each date placed in PoP table.
     * @param  timestamp $date      timestamp of date
     * @param  integer $abilityid id of ability for search in $this->abilitiesDateInfo.
     * @return string            formated cell
     */
    private function formatDateCell($date, $abilityid)
    {
      global $CFG;
      $timeUnit = '';

      if (is_null($date) || $date == '') {
        return '';
      }

      switch ($this->abilitiesDateInfo[$abilityid]->intervaltype) {
        case 'd':
          $timeUnit = 'day';
          break;
        case 'm':
          $timeUnit = 'month';
          break;
        case 'y':
          $timeUnit = 'year';
          break;
      }


      $dateFormulaString = '+'.$this->abilitiesDateInfo[$abilityid]->intervaldate.' '.$timeUnit;

      $nowdate = date('Y-m-d h:i:s', strtotime('+'.$CFG->block_mcdpde_expiredays.' day', time()));

      $dateToShow = date('Y-m-d h:i:s', strtotime($dateFormulaString, $date));

      $givenDate = date('d/m/Y', strtotime($dateFormulaString, $date));

      // $color = 0;
      // if ($nowdate > $dateToShow)
      //   $color = 1;

      $result = '';
      if (!$this->is_downloading() && $nowdate > $dateToShow) {
        $result = '<div style="color:'.$CFG->block_mcdpde_expiredate.';font-weight: bold;">'
                  .$givenDate.'</div>';
      } else {
        $result = $givenDate;
      }

      //return $dateToShow . ' ' . $nowdate. ' -- '.$color;
      return $result;
    }

    /**
   * Build table witch objectives summary, instead of groupby in SQL
   * @return table rows
   */
    public function build_table()
    {
        if ($this->rawdata instanceof \Traversable && !$this->rawdata->valid()) {
            return;
        }
        if (!$this->rawdata) {
            return;
        }
        $userid = 0;
        $data = null;
        foreach ($this->rawdata as $row) {
            $colname = 'col'.$row->abilityid;
            if ($userid != $row->userid) {
                if (!is_null($data)) {
                    $this->addRowToTable($data);
                }
                $data = $row;
                $userid = $row->userid;
            }

            if (is_null($row->timemodified)) {
                $data->$colname = null;
            } else {
                //$date = new \DateTime($row->timemodified, \core_date::get_server_timezone_object());
                //$data->$colname = $date->format('d-m-Y');
                if (($row->totalgrades == 0) || is_null($row->sumgrade) || ($row->sumgrade == 0)) {
                    $data->$colname = null;
                  }
                  else {

                    // HERE is the format for date cells because here is the
                    // abilityid data.
                    $data->$colname = $this->formatDateCell(
                      levelabHelper::getLevelAB($row->abstring, $row->abtime, $row->abgrade, 1),
                      $row->abilityid
                    );
                  }
            }
        }
        if (!is_null($data)) {
            $this->addRowToTable($data);
        }

        if ($this->rawdata instanceof \core\dml\recordset_walk ||
        $this->rawdata instanceof moodle_recordset) {
            $this->rawdata->close();
        }
    }

    /**
   * Refactoring add row from original build_table method, only for
   * usability
   * @param \stdClass $row class with key column and value.
   */
    protected function addRowToTable($row)
    {
      $rowdata = $this->addEmptyCols($row);
      $formattedrow = $this->format_row($rowdata);
        $this->add_data_keyed($formattedrow, $this->get_row_class($row));
    }

    public function col_code($values)
    {
      //ue.NO_CIA, ue.NO_EMPLE, ue.NOMBRE, ue.APELLIDO, ue.NOMBRE_REST, ue.CODIGO_PAIS,
      return $values->no_cia.$values->no_emple;
    }

    public function col_country($values)
    {
      return get_string($values->codigo_pais,'countries');
    }

    public function col_consultor($values)
    {
      return $values->conombre.' '.$values->coapellido;
    }

    public function col_name($values)
    {
      return $values->nombre.' '.$values->apellido;
    }

    public function other_cols($colname, $value)
    {
        if (in_array($colname, $this->columns)) {

          return null;
        }
        return "not yet implement";
    }
}
