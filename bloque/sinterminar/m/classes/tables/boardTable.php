<?php
namespace block_mcdpde\tables;

use block_mcdpde\models\abilitiesModel;
use block_mcdpde\helpers\levelabHelper;

require_once $CFG->libdir.'/tablelib.php';

class boardTable extends \table_sql
{
    public $columns;

    private $colIds;

    public $abilities;

    public function __construct($uniqueid, $download, $areaCode =1)
    {
        parent::__construct($uniqueid);

        $headers = array(
          get_string('usercode', 'block_mcdpde'),
          get_string('name','block_mcdpde'));
          // get_string('country', 'block_mcdpde'),
          // get_string('consultant', 'block_mcdpde'),
          // get_string('restaurant', 'block_mcdpde'),

        // $this->columns = array('code', 'country', 'consultor', 'nombre_rest', 'name');
        $this->columns = array('code', 'name');
        $this->colIds = array();

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

            if (($row->totalgrades == 0) || is_null($row->sumgrade) || ($row->sumgrade == 0)) {
                $data->$colname = null;
            // } elseif (($row->sumgrade / $row->totalgrades == 2) && ($row->sumgrade % $row->totalgrades == 0)) {
            } else {
                $data->$colname = levelabHelper::getLevelAB($row->abstring, $row->abtime, $row->abgrade);
            }
            // elseif ($row->levelab == 'A' && !is_null($row->sumgrade)) {
            //     $data->$colname = 'A';
            // } elseif ($row->levelab == 'B' && !is_null($row->sumgrade)) {
            //     $data->$colname = 'B';
            // }
        }
        if (!is_null($data)) {
            //var_dump($data);
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
