<?php
namespace block_mcdpde\tables;

use block_mcdpde\models\abilitiesModel;
use block_mcdpde\models\categoriesModel;
use block_mcdpde\models\competenciesModel;

require_once $CFG->libdir.'/tablelib.php';

class medalTable extends \table_sql
{
    public $columns;

    public $categories;

    public $medals;
    private $competenciesNames;

    public function __construct($uniqueid, $download)
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

        $categoriesModel = new categoriesModel();

        $this->categories = $categoriesModel->getAllCategories();
        $this->medals = new \stdClass();

        foreach ($this->categories as $id => $category) {

              $headers[]=$category->name;
              $medalcatname = 'cat'.$id;
              $this->medals->$medalcatname = new \stdClass();
              $this->medals->$medalcatname->b = array();
              $this->medals->$medalcatname->cb = 0;
              $this->medals->$medalcatname->p = array();
              $this->medals->$medalcatname->cp = 0;
              $this->medals->$medalcatname->o = array();
              $this->medals->$medalcatname->co = 0;
              $this->columns[]='col'.$id;
        }

        $this->define_columns($this->columns);
        $this->define_headers($headers);
        $this->sortable(false);

        $this->createMedalStructure();

        //var_dump($this->medals);

    }

    private function resetMedalCount()
    {
      foreach ($this->competenciesnames as $competency) {
        $medalcatname = 'cat'.$competency->categoriesid;
        $this->medals->$medalcatname->cb = 0;
        $this->medals->$medalcatname->cp = 0;
        $this->medals->$medalcatname->co = 0;
      }
    }

    private function medalCount($categoryid, $medal)
    {
      $medalcatname = 'cat'.$categoryid;
      switch ($medal) {
        case 'O':
          $this->medals->$medalcatname->co++;
          break;
        case 'P':
          $this->medals->$medalcatname->cp++;
          break;
        case 'B':
          $this->medals->$medalcatname->cb++;
          break;
      }
    }

    private function createMedalStructure()
    {
      $this->competenciesnames = competenciesModel::getCategoryMedalInfo();
      foreach ($this->competenciesnames as $competency) {
        $medalcatname = 'cat'.$competency->categoriesid;
        switch ($competency->medal) {
          case 'O':
            $this->medals->$medalcatname->o[] = $competency->id;
            break;
          case 'P':
            $this->medals->$medalcatname->p[] = $competency->id;
            break;
          case 'B':
            $this->medals->$medalcatname->b[] = $competency->id;
            break;
        }
      }
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

    private function getMedal($categoryid)
    {
      $medalcatname = 'cat'.$categoryid;
      // return $this->medals->$medalcatname->cb.' '.count($this->medals->$medalcatname->b). ' - '.
      //   $this->medals->$medalcatname->cp.' '.count($this->medals->$medalcatname->p). ' - '.
      //   $this->medals->$medalcatname->co.' '.count($this->medals->$medalcatname->o);

      $result = '';
      if (count($this->medals->$medalcatname->b) > 0) {
          if ($this->medals->$medalcatname->cb == count($this->medals->$medalcatname->b)) {
            $result = 'B';
          } else {
            return $result;
          }
      } else {
        return $result;
      }

      if (count($this->medals->$medalcatname->p) > 0) {
          if ($this->medals->$medalcatname->cp == count($this->medals->$medalcatname->p)) {
            $result = 'P';
          } else {
            return $result;
          }
      } else {
        return $result;
      }

      if (count($this->medals->$medalcatname->o) > 0) {
          if ($this->medals->$medalcatname->co == count($this->medals->$medalcatname->o)) {
            $result = 'O';
          } else {
            return $result;
          }
      } else {
        return $result;
      }

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
        $categoryid = 0;
        foreach ($this->rawdata as $row) {
            if ($userid != $row->userid) {
                if (!is_null($data)) {
                  $colname = 'col'.$categoryid;
                  $data->$colname = $this->getMedal($categoryid) ;
                    $this->addRowToTable($data);
                }
                $data = $row;
                $this->resetMedalCount();
                foreach ($this->categories as $id => $category) {
                  $tmpcolname = 'col'.$id;
                  $data->$tmpcolname= '';
                }
                $userid = $row->userid;
                $categoryid = $row->categoryid;
            }

            if ($categoryid != $row->categoryid) {
              $colname = 'col'.$categoryid;
              $data->$colname = $this->getMedal($categoryid) ;
              $categoryid = $row->categoryid;
              $this->resetMedalCount();
            }
            if (!is_null($row->sumgrade) && ($row->totalgrades > 0))
              if ($row->sumgrade / $row->totalgrades == 2)
                $this->medalCount($row->categoryid, $row->medal);

        }
        if (!is_null($data)) {
          $colname = 'col'.$categoryid;
          $data->$colname = $this->getMedal($categoryid) ;
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
        $formattedrow = $this->format_row($row);
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
