<?php

namespace block_mcdpde\renders;
require_once("{$CFG->libdir}/tablelib.php");

use block_mcdpde\models\abilitiesModel;
use block_mcdpde\models\QueryModel;
use block_mcdpde\helpers\levelabHelper;

defined('MOODLE_INTERNAL') || die();
/**
 * pillarRender class, construct html for render a table.
 */
class resumeRender implements \renderable
{
    private $table;

    private $pagesize;
    private $useinitialsbar;

    public function __construct(\flexible_table $table,
                        $useinitialsbar = true, $areaCode = 1)
    {
        $this->table = $table;
        $this->useinitialsbar = $useinitialsbar;

        $abilitiesModel = new abilitiesModel();

        $this->abilities = $abilitiesModel->getAllAbilities($areaCode);

        $this->briefHeaders= array(
          get_string('competencylevel','block_mcdpde')
        );
        $this->briefColumns = array();
        $this->briefColumns[] = 0;
        $this->brief['advanced'][]=get_string('advanced','block_mcdpde');
        $this->brief['basic'][]=get_string('basic','block_mcdpde');
        $this->brief['blanc'][]=get_string('blanc','block_mcdpde');
        $this->brief['total'][]='<b>'.get_string('total','block_mcdpde').'</b>';

        foreach ($this->abilities as $id => $ability) {
            // if ( $download == '' ){
              $this->briefHeaders[]='<div class="col_rotated">'.$ability->ability.'</div>';
            // } else {
            //   $headers[]=$ability->ability;
            //   $this->briefHeaders[]=$ability->ability;
            // }
            // $this->columns[]='col'.$id;
            $this->brief['advanced'][$id]=0;
            $this->brief['basic'][$id]=0;
            $this->brief['blanc'][$id]=0;
            $this->brief['total'][$id]=0;
            $this->briefColumns[]=$id;
        }
    }

    public function populate(QueryModel $model)
    {
      $result = $model->getRecords();

      foreach ($result as $key => $row) {
        // echo "<p>";
        if (($row->totalgrades == 0) || is_null($row->sumgrade) || ($row->sumgrade == 0)) {
            $this->brief['blanc'][$row->abilityid]+=1;
            $this->brief['total'][$row->abilityid]+=1;
            // echo " N+ ";
        // } elseif (($row->sumgrade / $row->totalgrades == 2) && ($row->sumgrade % $row->totalgrades == 0)) {
        } else {
          $level = levelabHelper::getLevelAB($row->abstring, $row->abtime, $row->abgrade);
          if ($level == 'A') {
            $this->brief['advanced'][$row->abilityid]+=1;
            $this->brief['total'][$row->abilityid]+=1;
          } elseif ($level == 'B') {
            $this->brief['basic'][$row->abilityid]+=1;
            $this->brief['total'][$row->abilityid]+=1;
          } elseif ($level == '') {
            $this->brief['blanc'][$row->abilityid]+=1;
            $this->brief['total'][$row->abilityid]+=1;
          }
        }

        //  elseif ($row->levelab == 'A') {
        //     $this->brief['advanced'][$row->abilityid]+=1;
        //     $this->brief['total'][$row->abilityid]+=1;
        //     // echo " A+ ";
        // } else {
        //     $this->brief['basic'][$row->abilityid]+=1;
        //     $this->brief['total'][$row->abilityid]+=1;
        //     // echo " B+ ";
        // }
        // echo " $row->id $row->ability . $row->levelab . $row->sumgrade </p>";
      }
    }

    public function display()
    {
      $this->table->define_columns($this->briefColumns);
      $this->table->define_headers($this->briefHeaders);
      $this->table->setup();
      foreach ($this->brief as $row) {
          $this->table->add_data(array_values($row));
      }
      $this->table->finish_output();
    }

    /**
     * Set the value of Title.
     *
     * @param string title
     *
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value of Pagesize.
     *
     * @param int pagesize
     *
     * @return self
     */
    public function setPagesize(int $pagesize)
    {
        $this->pagesize = $pagesize;

        return $this;
    }

    /**
     * Set the value of Useinitialsbar.
     *
     * @param bool useinitialsbar
     *
     * @return self
     */
    public function setUseinitialsbar(boolean $useinitialsbar)
    {
        $this->useinitialsbar = $useinitialsbar;

        return $this;
    }
}
