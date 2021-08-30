<?php

namespace block_mcdpde\renders;
use block_mcdpde\models\abilitiesModel;
use block_mcdpde\forms\reportFilter;

defined('MOODLE_INTERNAL') || die();
/**
 * pillarRender class, construct html for render a table.
 */
class boardRender implements \renderable
{
    private $table;

    private $title;
    private $pagesize;
    private $useinitialsbar;

    public function __construct(\table_sql $table, $title,
                        $pagesize = 10, $useinitialsbar = true, $areaCode=1)
    {
        $this->table = $table;
        $model = new abilitiesModel();
        $this->pagesize = $pagesize*$model->countAllAbilities($areaCode);
        $this->title = $title;
        $this->useinitialsbar = $useinitialsbar;
    }

    public function display($viewFilter = false, $download = false)
    {
      global $DB;
        if ($download == false) {
          echo \html_writer::start_tag('div', array('class' => 'detailed-content'));
          echo \html_writer::tag('h2', $this->title,
                            array('class' => 'pillar-course-header'));


          if ($viewFilter == true) {
            $filter = new reportFilter();
            $filter->display();
          }
        }

        $this->table->out($this->pagesize, $this->useinitialsbar);

        if ($download == false)
          echo \html_writer::end_tag('div');
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
