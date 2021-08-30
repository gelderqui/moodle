<?php
namespace block_mcdpde\models;
/**
 * base class for extends in new models
 */
class mcdpdeModelBase
{
    /**
   * query SELECT section
   * @var string
   */
  protected $querySelect;
  /**
   * query FROM section
   * @var string
   */
  protected $queryFrom;
  /**
   * query WHERE section
   * @var string
   */
  protected $queryWhere;
  /**
   * Params to use in query
   * @var array
   */
  protected $queryParams;

  public function __construct()
  {
      $this->queryParams=array();
      $this->queryWhere=null;
  }

  /**
   * Get the value of query SELECT section
   *
   * @return string
   */
  public function getQuerySelect()
  {
      return $this->querySelect;
  }

  /**
   * Get the value of query FROM section
   *
   * @return string
   */
  public function getQueryFrom()
  {
      return $this->queryFrom;
  }

  /**
   * Get the value of query WHERE section
   *
   * @return string
   */
  public function getQueryWhere()
  {
      if (is_null($this->queryWhere)) {
          return '1=1';
      } else {
          return $this->queryWhere;
      }
  }

  /**
   * Get the value of Params to use in query
   *
   * @return array
   */
  public function getQueryParams()
  {
      if (count($this->queryParams)==0) {
          return array();
      } else {
          return $this->queryParams;
      }
  }

  public function getSQLQuery()
  {
    return 'SELECT '.$this->querySelect.' FROM '.$this->queryFrom.
          ' WHERE '.$this->queryWhere.' PARAMS = {'.implode(',', $this->queryParams).'}';
  }

  public function getBasicSQL()
  {
      return 'SELECT '.$this->querySelect.' FROM '.$this->queryFrom;
  }

  public function getSQL()
  {
    $sql='SELECT ' . $this->querySelect;
    $sql .=' FROM ' . $this->queryFrom;
    $sql .=' WHERE ' . $this->queryWhere;
    return $sql;
  }
}
