<?php

namespace block_mcdpde\models;

/**
 * query model for exec complex queries to DB.
 */
class QueryModel extends mcdpdeModelBase
{
    const MYSQL = 1;
    const ORACLE = 2;

    private $selectServerCount;
    private $fromServerCount;
    private $fromEndServerCount;
    private $DBMSType;

    private $abStringsSQL;

    private $medals;

    public function __construct($DBMSType = self::MYSQL)
    {
      global $CFG;
      parent::__construct();
      switch ($CFG->dbtype) {
        case 'mariadb':
          $this->DBMSType = self::MYSQL;
          break;
        case 'oci':
          $this->DBMSType = self::ORACLE;
          break;
      }
      switch ($this->DBMSType) {
        case self::MYSQL:
          $this->selectServerCount = '@row := @row +1 as id, ';
          $this->fromServerCount = '(select @row := 0) r, ';
          $this->fromEndServerCount = '';
          $this->abStringsSQL =
            "REPLACE(GROUP_CONCAT( coalesce( ac.levelab,',') ORDER BY ac.levelab SEPARATOR ',' ),',,',',') AS abstring,
            REPLACE(GROUP_CONCAT( coalesce( cu.timemodified,',') ORDER BY ac.levelab SEPARATOR ',' ),',,',',') AS abtime,
            REPLACE(GROUP_CONCAT( coalesce( cu.grade,',') ORDER BY ac.levelab SEPARATOR ',' ),',,',',') AS abgrade";

          break;
        case self::ORACLE:
          $this->selectServerCount = 'rownum as id, csql.* ';
          $this->fromServerCount = ' ( ';
          $this->fromEndServerCount = ' ) csql ';
          $this->abStringsSQL =
             "REPLACE(LISTAGG(NVL(TO_CHAR(ac.levelab),','), ',') WITHIN GROUP (ORDER BY ac.levelab),',,',',') AS abstring,
             REPLACE(LISTAGG(NVL(TO_CHAR(cu.timemodified),','), ',') WITHIN GROUP (ORDER BY ac.levelab),',,',',') AS abtime,
             REPLACE(LISTAGG(NVL(TO_CHAR(cu.grade),','), ',') WITHIN GROUP (ORDER BY ac.levelab),',,',',') AS abgrade";
          break;
      }
      $this->medals = false;
    }

    public function setMedals($enable = true)
    {
      $this->medals = $enable;
    }

    public function getUserPuesto()
    {
      global $USER, $DB;

      $usr = $DB->get_record('user', array('id' => $USER->id) );

      $sql = "SELECT ep.*, ep.CODIGO AS NO_EMPLE fROM LEA_ASIGNA_PERFIL_MV ep
      WHERE ep.CODIGO = :idnumber AND ep.NO_CIA = :institution AND ep.TIPO = 'C' ";
      $params = array('idnumber' => $usr->idnumber, 'institution' => $usr->institution);

      $sql2 = "SELECT le.NO_CIA, le.NO_EMPLE, le.NOMBRE, le.APELLIDO, lap.PERFIL,le.CODIGO_PAIS
      FROM LEA_EMPLEADO_MV le
        INNER JOIN LEA_ASIGNA_PERFIL_MV lap
          ON le.NO_CIA = lap.NO_CIA AND le.COD_PUESTO = lap.CODIGO
        WHERE le.STATUS_EMP!='I' AND lap.TIPO = 'P' AND le.NO_EMPLE = :idnumber AND le.NO_CIA = :institution ";

      $mcdusr = $DB->get_record_sql($sql,$params);
      if ($mcdusr) {
        return $mcdusr;
      }

      $mcdusr = $DB->get_record_sql($sql2,$params);
      return $mcdusr;

    }
/**/ 
    public function getRecordUserEmpleado($userid = 0, $areaCode = null)
    {
      global $DB;

      return $DB->get_records_sql($this->getUserEmpleadoSQL($userid, $areaCode));
    }

    public function getAsignaRest($cod_asigna)
    {
      global $DB;

      $sql = "SELECT ac.* FROM LEA_ASIGNA_REST_MV ac WHERE COD_ASIGNA = :codasigna";
      $params = array('codasigna' => $cod_asigna );

      return $DB->get_records_sql($sql, $params);
    }

    public function getCountAsignaRest($cod_asigna)
    {
      return count($this->getAsignaRest($cod_asigna));
    }

    public function getEmpleado($nocia, $noemple) {
      global $DB;
      $sql = "SELECT * FROM LEA_EMPLEADO_MV e WHERE e.STATUS_EMP!='I' AND e.NO_CIA = :nocia AND e.NO_EMPLE = :noemple";
      $params = array('nocia' => $nocia, 'noemple' => $noemple);
      return $DB->get_record_sql($sql, $params);
    }

    public function getAsignaPerfil($nocia, $noemple) {
      global $DB;
      $sql = "SELECT * FROM LEA_ASIGNA_PERFIL_MV lap WHERE lap.NO_CIA = :nocia AND lap.CODIGO = :noemple";
      $params = array('nocia' => $nocia, 'noemple' => $noemple);
      return $DB->get_record_sql($sql, $params);
    }

    public function getUserEmpleadoSQL($userid = 0, $areaCode = null)
    {
      $user = $this->getUserPuesto();
      $restSelectItems='';
      if ($userid > 0) {
        $sql = 'SELECT u.id, u.username, e.* '.$restSelectItems.
         'FROM {user} u INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
         WHERE e.STATUS_EMP!=\'I\' AND u.id = '.$userid;
        return $sql;
      }

      $mcdpdeCategories = $areaCode;

      $areaCodeOperator = "=";
      if ($areaCode == 1 || $areaCode == 6) {
        $areaCodeOperator = ">";
        $areaCode = 0;
        $mcdpdeCategories = $areaCode;
      }

      // redirect queries to restaurant id
      if ($areaCode == 100) {
        $areaCodeOperator = ">";
        $areaCode = 0;
        $mcdpdeCategories = 100;
      }

      // clean pefil data
      $user->perfil = preg_replace('/\s+/','',$user->perfil);

      $filterCountry = " AND e.NO_CIA=".$user->no_cia.' ';

      // do not filter GC, GE and GO roles for only one country
      if ($user->perfil == 'GC' ||
          $user->perfil == 'GE' ||
          $user->perfil == 'GO' ) {
        $filterCountry = ' ';
      }

      $link = '';
      $sql_COD_ASIGNA = "SELECT u.id, u.username, e.*
      FROM LEA_EMPLEADO_MV e
       INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
       WHERE e.STATUS_EMP!='I' AND e.COD_AREA".$areaCodeOperator.$areaCode.$filterCountry.
       " AND e.NO_REST IN (SELECT lar.NO_REST FROM LEA_ASIGNA_REST_MV lar
         INNER JOIN LEA_ASIGNA_PERFIL_MV lap ON lar.COD_ASIGNA = lap.COD_ASIGNA
         WHERE lap.NO_CIA = ".$user->no_cia." AND lar.NO_CIA_REST = ".
            $user->no_cia." AND lap.CODIGO = ".$user->no_emple.")";

      switch ($user->perfil) {
        case 'CH' :
        case 'GR' :
          $empleado = $this->getEmpleado($user->no_cia, $user->no_emple);
          $sql = 'SELECT u.id, u.username, e.*
          FROM LEA_EMPLEADO_MV e
	         INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
           WHERE e.STATUS_EMP!=\'I\' AND e.NO_REST = '.$empleado->no_rest.' AND e.NO_CIA = '.$empleado->no_cia.
                 ' AND e.COD_AREA'.$areaCodeOperator.$areaCode;
          return $sql;
          break;
        case 'CO':
        case 'RG':
        case 'CE':
            return $sql_COD_ASIGNA;
          break;
        case 'GO':
        case 'GE':
        case 'GC':
            $asignaPerfil = $this->getAsignaPerfil($user->no_cia, $user->no_emple);
            if ($this->getCountAsignaRest($asignaPerfil->cod_asigna) == 0 ) {
              $sql = 'SELECT u.id, u.username, e.*
              FROM LEA_EMPLEADO_MV e
    	         INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
               WHERE e.STATUS_EMP!=\'I\' AND e.COD_AREA'.$areaCodeOperator.$areaCode;
              return $sql;
            }
            else {
              $sql_COD_ASIGNA = "SELECT u.id, u.username, e.*
              FROM LEA_EMPLEADO_MV e
               INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
               WHERE e.STATUS_EMP!='I' AND e.COD_AREA".$areaCodeOperator.$areaCode.$filterCountry.
               " AND e.NO_REST IN (
                 SELECT ac.NO_REST FROM LEA_ASIGNA_REST_MV ac
                  WHERE COD_ASIGNA = ".$asignaPerfil->cod_asigna." )";
              return $sql_COD_ASIGNA;
            }
          break;
      }
      return '';
    }

    public function getUserEmpleadoSQLFiltro($userid = 0)
    {
      $user = $this->getUserPuesto();


      $restSQL = '';
      $restSelectItems = '';

      $restSQL='LEA_EMPLEADO_MV';
      $restSelectItems = '';

      if ($userid > 0) {
        $sql = 'SELECT u.*,e.* '.$restSelectItems.
         'FROM {user} u INNER JOIN LEA_EMPLEADO_MV e ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
         WHERE e.STATUS_EMP!=\'I\' AND u.id = '.$userid;
        return $sql;
      }

      $link = '';
      $sql_COD_ASIGNA = "SELECT u.id,
      u.auth,
      u.confirmed,
      u.policyagreed,
      u.deleted,
      u.suspended,
      u.mnethostid,
      u.username,
      u.password,
      u.idnumber,
      u.idnumber ||' ' || u.firstname as firstname,
      u.lastname,
      u.email,
      u.emailstop,
      u.icq,
      u.skype,
      u.yahoo,
      u.aim,
      u.msn,
      u.phone1,
      u.phone2,
      u.institution,
      u.department,
      u.address,
      u.city,
      u.country,
      u.lang,
      u.calendartype,
      u.theme,
      u.timezone,
      u.firstaccess,
      u.lastaccess,
      u.lastlogin,
      u.currentlogin,
      u.lastip,
      u.secret,
      u.picture,
      u.url,
      u.description,
      u.descriptionformat,
      u.mailformat,
      u.maildigest,
      u.maildisplay,
      u.autosubscribe,
      u.trackforums,
      u.timecreated,
      u.timemodified,
      u.trustbitmask,
      u.imagealt,
      u.lastnamephonetic,
      u.firstnamephonetic,
      u.middlename,
      u.alternatename,u.moodlenetprofile, e.*
      FROM LEA_EMPLEADO_MV e
       INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
       WHERE e.STATUS_EMP!='I' AND e.NO_REST IN (SELECT lar.NO_REST FROM LEA_ASIGNA_REST_MV lar
         INNER JOIN LEA_ASIGNA_PERFIL_MV lap ON lar.COD_ASIGNA = lap.COD_ASIGNA
         WHERE lap.NO_CIA = ".$user->no_cia." AND lap.CODIGO = ".$user->no_emple.")";

      switch (preg_replace('/\s+/','',$user->perfil)) {
        case 'CH' :
        case 'GR' :
          $empleado = $this->getEmpleado($user->no_cia, $user->no_emple);
          $sql = "SELECT u.id,
          u.auth,
          u.confirmed,
          u.policyagreed,
          u.deleted,
          u.suspended,
          u.mnethostid,
          u.username,
          u.password,
          u.idnumber,
          u.idnumber ||' ' || u.firstname as firstname,
          u.lastname,
          u.email,
          u.emailstop,
          u.icq,
          u.skype,
          u.yahoo,
          u.aim,
          u.msn,
          u.phone1,
          u.phone2,
          u.institution,
          u.department,
          u.address,
          u.city,
          u.country,
          u.lang,
          u.calendartype,
          u.theme,
          u.timezone,
          u.firstaccess,
          u.lastaccess,
          u.lastlogin,
          u.currentlogin,
          u.lastip,
          u.secret,
          u.picture,
          u.url,
          u.description,
          u.descriptionformat,
          u.mailformat,
          u.maildigest,
          u.maildisplay,
          u.autosubscribe,
          u.trackforums,
          u.timecreated,
          u.timemodified,
          u.trustbitmask,
          u.imagealt,
          u.lastnamephonetic,
          u.firstnamephonetic,
          u.middlename,
          u.alternatename,
          u.moodlenetprofile,e.*
          FROM LEA_EMPLEADO_MV e
	         INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
           WHERE e.STATUS_EMP!='I' AND e.NO_REST = ".$empleado->no_rest.' AND e.NO_CIA = '.$empleado->no_cia;
          return $sql;
          break;
        case 'CO':
        case 'RG':
        case 'CE':
            return $sql_COD_ASIGNA;
          break;
        case 'GO':
        case 'GE':
        case 'GC':
            $asignaPerfil = $this->getAsignaPerfil($user->no_cia, $user->no_emple);
            if ($this->getCountAsignaRest($asignaPerfil->cod_asigna) == 0 ) {
              $sql = "SELECT u.id,
              u.auth,
              u.confirmed,
              u.policyagreed,
              u.deleted,
              u.suspended,
              u.mnethostid,
              u.username,
              u.password,
              u.idnumber,
              u.idnumber ||' ' || u.firstname as firstname,
              u.lastname,
              u.email,
              u.emailstop,
              u.icq,
              u.skype,
              u.yahoo,
              u.aim,
              u.msn,
              u.phone1,
              u.phone2,
              u.institution,
              u.department,
              u.address,
              u.city,
              u.country,
              u.lang,
              u.calendartype,
              u.theme,
              u.timezone,
              u.firstaccess,
              u.lastaccess,
              u.lastlogin,
              u.currentlogin,
              u.lastip,
              u.secret,
              u.picture,
              u.url,
              u.description,
              u.descriptionformat,
              u.mailformat,
              u.maildigest,
              u.maildisplay,
              u.autosubscribe,
              u.trackforums,
              u.timecreated,
              u.timemodified,
              u.trustbitmask,
              u.imagealt,
              u.lastnamephonetic,
              u.firstnamephonetic,
              u.middlename,
              u.alternatename, u.moodlenetprofile,e.*
              FROM LEA_EMPLEADO_MV e
    	         INNER JOIN {user} u ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber ";
              return $sql;
            }
            else {
              return $sql_COD_ASIGNA;
            }
          break;
      }
      return '';
    }

    public function configureBoardBasic($userid = 0,
            $arrayUserId = null,
            $arrayCountry = null,
            $arrayRestaurant = null,
            $name = null,
            $arrayConsultant = null,
            $areaCode = 1,
            $medalQuery = 0)
    {
      global $DB;

      $this->querySelect = $this->selectServerCount;

      $selectFields = ' ab.id AS abilityid, MAX(ab.ability) AS ability,
        MIN(lvl.levelab) AS levelab, MAX(ac.medal) as medal,
        uct.userid,
        MAX(ue.NO_CIA) as no_cia, MAX(ue.NO_EMPLE) as no_emple, MAX(ue.NOMBRE) AS NOMBRE,
        MAX(ue.APELLIDO) as APELLIDO,
        MAX(cu.competencyid), MAX(uct.shortname) as shortname, MAX(cu.timemodified) AS timemodified,
        SUM( cu.grade ) AS sumgrade, COUNT(cu.userid) AS totalgrades,
        MAX(mc.id) as categoryid, MAX(mc.name) as categoryname, MAX(mc.position) as categoriposition ';

      // add support for concat strings from group
      $selectFields .= ', '.$this->abStringsSQL;

        if ($this->DBMSType == self::MYSQL) {
          $this->querySelect .= $selectFields;
        }
      $this->queryFrom = $this->fromServerCount;

      if ($this->DBMSType == self::ORACLE) {
        $this->queryFrom .= 'SELECT '.$selectFields .' FROM ';
      }

      $this->queryFrom .=
      '(SELECT c.id as competencyid, c.shortname, u.id as userid, u.username
          FROM  {competency} c,{user} u) uct
      INNER JOIN ('.$this->getUserEmpleadoSQL($userid, $areaCode).') ue ON uct.userid = ue.id
      LEFT JOIN {competency_usercompcourse} cu ON uct.competencyid = cu.competencyid AND uct.userid = cu.userid
      INNER JOIN {mcdpde_ability_competency} ac ON uct.competencyid = ac.competencyid
      INNER JOIN {mcdpde_abilities} ab ON ab.id = ac.abilityid
      INNER JOIN {mcdpde_categories} mc ON ab.categoriesid = mc.id
      LEFT JOIN
      (
        SELECT cui.userid, MIN(aci.levelab) as levelab, abi.id as abilityid
          FROM {mcdpde_ability_competency} aci
            INNER JOIN {competency_usercompcourse} cui ON cui.competencyid = aci.competencyid
            INNER JOIN {mcdpde_abilities} abi ON abi.id = aci.abilityid
            WHERE cui.grade IS NOT NULL
            GROUP BY cui.userid, abi.id
            ORDER BY cui.userid, abi.id
      ) lvl ON lvl.userid = uct.userid AND lvl.abilityid = ab.id';

      $mcdpdeCategories = $areaCode;
      $areaCodeOperator = "=";
      $mcdpdeCategoriesOperrator = '=';
     if ($areaCode == 1 && $medalQuery == 0) {
        $areaCodeOperator = " = ";
        $areaCode = 1;
        $mcdpdeCategories = $areaCode." OR  lvl.abilityid=167 OR  lvl.abilityid=161  OR  lvl.abilityid=162 ";
        $mcdpdeCategoriesOperrator = $areaCodeOperator;
    }

      // redirect queries to restaurant id
      if ($areaCode == 100) {
        $areaCodeOperator = ">";
        $areaCode = 0;
        $mcdpdeCategories = 100;
        $mcdpdeCategoriesOperrator = '=';
      }

      $this->queryWhere = '( mc.areaid'.$mcdpdeCategoriesOperrator.$mcdpdeCategories.' ) ';
      if ($userid == 0) {
        $andtext = ' AND ';
        if (!is_null($arrayUserId) && count($arrayUserId) > 0)  {
            $this->queryWhere .= $andtext.' uct.userid IN ( '.implode(",", $arrayUserId ).' )';
            $andtext = ' AND ';
        }
        if (!is_null($arrayCountry) && count($arrayCountry) > 0) {
          $this->queryWhere .= $andtext.' ue.codigo_pais IN ( '.implode(",", $arrayCountry ).' )';
          $andtext = ' AND ';
        }

        if (!is_null($arrayConsultant) && count($arrayConsultant) > 0) {
          $this->queryWhere .= $andtext.' ue.COD_CONS IN ( '.implode(",", $arrayConsultant ).' )';
          $andtext = ' AND ';
        }

        if (!is_null($arrayRestaurant) && count($arrayRestaurant) > 0) {
          $clearArray = array();
          $countryArray = array();
          foreach ($arrayRestaurant as $rest) {
            $countryArray[] = substr($rest,0,2);
            $clearArray[]=substr($rest,2);
          }
          //var_dump($clearArray);
          $this->queryWhere .= $andtext.' ue.no_rest IN ( '.implode(",", $clearArray ).' )';
          $this->queryWhere .= ' AND ue.codigo_pais IN ( \''.implode("','", $countryArray ).'\' )';
          $andtext = ' AND ';
        }

        if (!is_null($name) && strlen($name) > 0) {
          $name = "%".$name."%";
          $this->queryWhere .= $andtext.' '.$DB->sql_like('ue.NOMBRE',':name', false, false).
                    ' OR '.$DB->sql_like('ue.APELLIDO', ':lastname', false, false);
          $andtext = ' AND ';
          $this->queryParams['name'] = $name;
          $this->queryParams['lastname'] = $name;

        }

        if ($this->queryWhere == '') {
          $this->queryWhere = '1=1';
        }
      }
      else {
        $this->queryWhere = ' uct.userid = :userid ';
        $this->queryParams['userid'] = $userid;
      }
      $this->queryWhere .= ' GROUP BY ab.id, uct.userid ORDER BY uct.userid, categoriposition, abilityid';
      if ($this->medals == false) {
        $this->queryWhere .= ', levelab';
      }
      else {
        $this->queryWhere .= ', medal';
      }
      $this->queryWhere .= $this->fromEndServerCount;
    }

    public function getCountSQL()
    {
      return 'SELECT COUNT(1) FROM ('.$this->getSQL().') a';
    }

    public function getRecords()
    {
      global $DB;

      return $DB->get_records_sql($this->getSQL(), $this->getQueryParams());
    }
}
