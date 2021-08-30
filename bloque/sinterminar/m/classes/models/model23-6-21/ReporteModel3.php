<?php 
namespace block_mcdpde\models;
class ReporteModel2 extends mcdpdeModelBase{
    // private $queryWhere ;
    // private $queryParams ;
    
    public function __constuct(){

    }

    // encabezado 1
    public function query1(){
        $query = "select curso.id,examenes.examenes as  examenes,curso.fullname
        from
        (select c.id, c.fullname
        from m_course c
        inner join m_customfield_data cd on cd.instanceid=c.id
        where c.visible=1 and cd.value like '%2%' and cd.fieldid=1
        order by c.id)curso
        left join 
        (select q.course, count(q.id) examenes
        from m_quiz q
        inner join m_grade_items gt on gt.itemname=q.name
        where gt.hidden=0
        group by q.course)examenes
        on examenes.course=curso.id";
        $result  =  $this->getBurnedQuery($query);
        return $result;

    }
    // encabezado 2
    public function query2(){

        $query = "select q.id, q.name,q.course,cursos.examenes,cursos.color ,cursos.colorletra
        from m_quiz q
        inner join m_grade_items gt on gt.itemname=q.name
        inner join m_customfield_data cd on cd.instanceid=q.course
        inner join m_course c on q.course=c.id
        left join
        (select curso.id,examenes.examenes as  examenes,curso.fullname,color.color,colorletra.colorletra,orden.orden
        from
        (select c.id, c.fullname
        from m_course c
        inner join m_customfield_data cd on cd.instanceid=c.id
        where cd.value like '%2%' and cd.fieldid=24
        order by c.id)curso
        left join 
        (select q.course, count(q.id) examenes
        from m_quiz q
        inner join m_grade_items gt on gt.itemname=q.name
        where gt.hidden=0
        group by q.course)examenes
        on examenes.course=curso.id
        LEFT JOIN
        (SELECT cd.value AS color, cd.fieldid, cd.instanceid
        FROM m_course c
        LEFT JOIN m_customfield_data cd
        ON c.id=cd.instanceid
        WHERE cd.fieldid=1) color
        ON color.instanceid=curso.id
        LEFT JOIN
        (SELECT cd.value AS colorletra, cd.fieldid, cd.instanceid
        FROM m_course c
        LEFT JOIN m_customfield_data cd
        ON c.id=cd.instanceid
        WHERE cd.fieldid=21) colorletra
        ON colorletra.instanceid=curso.id
        
                LEFT JOIN
        (SELECT cd.value AS orden, cd.fieldid, cd.instanceid
        FROM m_course c
        LEFT JOIN m_customfield_data cd
        ON c.id=cd.instanceid
        WHERE cd.fieldid=22) orden
        ON orden.instanceid=curso.id
        
        )cursos
        on cursos.id=q.course
        where gt.hidden=0 and c.visible=1 and cd.value like '%2%' and cd.fieldid=24
        order by To_number(cursos.orden), q.course,q.id
        ";
        $result  =  $this->getBurnedQuery($query);
        return $result;
    }
    
    // encabezado 3
    public function query3($params,$pais,$restaurante,$perfil,$co,$gr){
    //public function query3($params,$pais,$restaurante,$perfil){
      if($co!=null)$prueba=1;
      if($gr!=null)$prueba=2;
      if($co==null and $gr==null)$prueba=null;
      if($co!=null and $gr!=null)$prueba=null;
    if($prueba==null){
      $query ="
      SELECT    Row_number() OVER (ORDER BY examenes.userid) AS id,
      examenes.quizid,
      examenes.course,
      examenes.userid,
      examenes.NAME,
      examenes.nombre,
      examenes.desc_puesto,
      examenes.no_cia,
      examenes.no_emple,
      respuesta.fecha,
      respuesta.sumgrades,
      examenes.nombre_rest,
      examenes.codigo_pais,
      examenes.perfil,
      examenes.no_rest,
      examenes.examenes,
      examenes.minima,
      examenes.color,(
      CASE
                WHEN respuesta.sumgrades>=To_number(examenes.minima) THEN 1
                ELSE 0
      END) AS ganado
FROM      (
               SELECT   *
               FROM     (
                                   SELECT     q.id AS quizid,
                                              q.NAME,
                                              q.course,
                                              examenes.examenes,
                                              minima.minima,
                                              color.color,orden.orden
                                   FROM       m_quiz q
                                   INNER JOIN m_grade_items gt
                                   ON         gt.itemname=q.NAME
                                   INNER JOIN m_customfield_data cd
                                   ON         cd.instanceid=q.course
                                   INNER JOIN m_course c
                                   ON         q.course=c.id
                                   LEFT JOIN
                                              (
                                                         SELECT     q.course,
                                                                    Count(q.id) AS examenes
                                                         FROM       m_quiz q
                                                         INNER JOIN m_grade_items gt
                                                         ON         gt.itemname=q.NAME
                                                         WHERE      gt.hidden=0
                                                         GROUP BY   q.course)examenes
                                   ON         examenes.course=c.id
                                   LEFT JOIN
                                              (
                                                        SELECT    cd.value AS minima,
                                                                  cd.fieldid,
                                                                  cd.instanceid
                                                        FROM      m_course c
                                                        LEFT JOIN m_customfield_data cd
                                                        ON        c.id=cd.instanceid
                                                        WHERE     cd.fieldid=25)minima
                                   ON         minima.instanceid=c.id
                                   LEFT JOIN
                                  (
                                            SELECT    cd.value AS color,
                                                      cd.fieldid,
                                                      cd.instanceid
                                            FROM      m_course c
                                            LEFT JOIN m_customfield_data cd
                                            ON        c.id=cd.instanceid
                                            WHERE     cd.fieldid=1) color
                                   ON         color.instanceid=c.id
                                   
                                   
                                   
                                   
                                                                      LEFT JOIN
                                   (
                                  SELECT    cd.value AS orden,
                                                      cd.fieldid,
                                                      cd.instanceid
                                            FROM      m_course c
                                            LEFT JOIN m_customfield_data cd
                                            ON        c.id=cd.instanceid
                                            WHERE     cd.fieldid=22) orden
                                   ON         orden.instanceid=c.id
                                   
                                   
                                   
                                   WHERE      gt.hidden=0
                                   AND        c.visible=1
                                   AND        cd.value LIKE '%2%'
                                   AND        cd.fieldid=24)examen,
                        (
                                   SELECT     u.id AS userid,
                                              u.username,
                                                         Concat (e.nombre,concat(' ',e.apellido)) AS nombre,
                                              e.desc_puesto,
                                              e.no_cia,
                                              e.no_emple,
                                              e.nombre_rest,
                                              e.codigo_pais,
                                              e.cod_puesto,
                                              ep.perfil,
                                              e.no_rest
                                   FROM       m_user u
                                   INNER JOIN lea_empleado_mv e
                                   ON         e.no_cia=u.institution
                                   AND        e.no_emple=u.idnumber
                                   INNER JOIN lea_asigna_perfil_mv ep
                                   ON         ep.no_cia=e.no_cia
                                   AND        (
                                                         ep.codigo=e.no_emple
                                              OR         ep.codigo=e.cod_puesto)
                                              WHERE      u.id $params)usuarios
                                              ORDER BY usuarios.userid,
                                              examen.course,
                                              examen.quizid)examenes
                      LEFT JOIN
                            (
                                       SELECT     respondido.*
                                       FROM       (
                                                             SELECT     qa.quiz,
                                                                        qa.userid,
                                                                        qa.attempt,
                                                                        to_char(to_date('1970-01-01','YYYY-MM-DD') + numtodsinterval(qa.timefinish,'SECOND'),'YYYY-MM-DD HH24:MI:SS') AS fecha,
                                                                        (qa.sumgrades                              *q.grade/q.sumgrades)                                              AS sumgrades
                                                             FROM       m_quiz_attempts qa
                                                             INNER JOIN m_quiz q
                                                             ON         qa.quiz=q.id
                                                             INNER JOIN m_grade_items gt
                                                             ON         gt.itemname=q.NAME
                                                             INNER JOIN m_customfield_data cd
                                                             ON         cd.instanceid=q.course
                                                             WHERE      gt.hidden=0
                                                             AND        cd.value LIKE '%2%'
                                                             AND        cd.fieldid=24)respondido
                                       RIGHT JOIN
                                                  (
                                                             SELECT     count(*) AS intentos,
                                                                        qa.quiz,
                                                                        qa.userid
                                                             FROM       m_quiz_attempts qa
                                                             INNER JOIN m_quiz q
                                                             ON         qa.quiz=q.id
                                                             INNER JOIN m_grade_items gt
                                                             ON         gt.itemname=q.NAME
                                                             INNER JOIN m_customfield_data cd
                                                             ON         cd.instanceid=q.course
                                                             WHERE      gt.hidden=0
                                                             AND        cd.value LIKE '%2%'
                                                             AND        cd.fieldid=24
                                                             GROUP BY   qa.quiz,
                                                                        qa.userid)intentos
                                       ON         intentos.intentos=respondido.attempt
                                       AND        intentos.quiz=respondido.quiz
                                       AND        respondido.userid=intentos.userid)respuesta
                      ON        examenes.quizid=respuesta.quiz
                      AND       examenes.userid=respuesta.userid
      where examenes.codigo_pais like '%".$pais."%' and examenes.no_rest like '%".$restaurante."%' and examenes.perfil like '%".$perfil."%'
      order by examenes.userid, To_number(examenes.orden),examenes.quizid
      ";
    
      }if($prueba==1){
        $query ="
        SELECT    Row_number() OVER (ORDER BY examenes.userid) AS id,
        examenes.quizid,
        examenes.course,
        examenes.userid,
        examenes.NAME,
        examenes.nombre,
        examenes.desc_puesto,
        examenes.no_cia,
        examenes.no_emple,
        respuesta.fecha,
        respuesta.sumgrades,
        examenes.nombre_rest,
        examenes.codigo_pais,
        examenes.perfil,
        examenes.no_rest,
        examenes.examenes,
        examenes.minima,
        examenes.color,(
        CASE
                  WHEN respuesta.sumgrades>=To_number(examenes.minima) THEN 1
                  ELSE 0
        END) AS ganado
  FROM      (
                 SELECT   *
                 FROM     (
                                     SELECT     q.id AS quizid,
                                                q.NAME,
                                                q.course,
                                                examenes.examenes,
                                                minima.minima,
                                                color.color,orden.orden
                                     FROM       m_quiz q
                                     INNER JOIN m_grade_items gt
                                     ON         gt.itemname=q.NAME
                                     INNER JOIN m_customfield_data cd
                                     ON         cd.instanceid=q.course
                                     INNER JOIN m_course c
                                     ON         q.course=c.id
                                     LEFT JOIN
                                                (
                                                           SELECT     q.course,
                                                                      Count(q.id) AS examenes
                                                           FROM       m_quiz q
                                                           INNER JOIN m_grade_items gt
                                                           ON         gt.itemname=q.NAME
                                                           WHERE      gt.hidden=0
                                                           GROUP BY   q.course)examenes
                                     ON         examenes.course=c.id
                                     LEFT JOIN
                                                (
                                                          SELECT    cd.value AS minima,
                                                                    cd.fieldid,
                                                                    cd.instanceid
                                                          FROM      m_course c
                                                          LEFT JOIN m_customfield_data cd
                                                          ON        c.id=cd.instanceid
                                                          WHERE     cd.fieldid=25)minima
                                     ON         minima.instanceid=c.id
                                     LEFT JOIN
                                    (
                                              SELECT    cd.value AS color,
                                                        cd.fieldid,
                                                        cd.instanceid
                                              FROM      m_course c
                                              LEFT JOIN m_customfield_data cd
                                              ON        c.id=cd.instanceid
                                              WHERE     cd.fieldid=1) color
                                     ON         color.instanceid=c.id
                                     
                                     
                                     
                                     
                                                                        LEFT JOIN
                                     (
                                    SELECT    cd.value AS orden,
                                                        cd.fieldid,
                                                        cd.instanceid
                                              FROM      m_course c
                                              LEFT JOIN m_customfield_data cd
                                              ON        c.id=cd.instanceid
                                              WHERE     cd.fieldid=22) orden
                                     ON         orden.instanceid=c.id
                                     
                                     
                                     
                                     WHERE      gt.hidden=0
                                     AND        c.visible=1
                                     AND        cd.value LIKE '%2%'
                                     AND        cd.fieldid=24)examen,
                          (
                                     SELECT     u.id AS userid,
                                                u.username,
                                                           Concat (e.nombre,concat(' ',e.apellido)) AS nombre,
                                                e.desc_puesto,
                                                e.no_cia,
                                                e.no_emple,
                                                e.nombre_rest,
                                                e.codigo_pais,
                                                e.cod_puesto,
                                                ep.perfil,
                                                e.no_rest
                                     FROM       m_user u
                                     INNER JOIN lea_empleado_mv e
                                     ON         e.no_cia=u.institution
                                     AND        e.no_emple=u.idnumber
                                     INNER JOIN lea_asigna_perfil_mv ep
                                     ON         ep.no_cia=e.no_cia
                                     AND        (
                                                           ep.codigo=e.no_emple
                                                OR         ep.codigo=e.cod_puesto)
                                                WHERE      u.id $params)usuarios
                                                ORDER BY usuarios.userid,
                                                examen.course,
                                                examen.quizid)examenes
                        LEFT JOIN
                              (
                                         SELECT     respondido.*
                                         FROM       (
                                                               SELECT     qa.quiz,
                                                                          qa.userid,
                                                                          qa.attempt,
                                                                          to_char(to_date('1970-01-01','YYYY-MM-DD') + numtodsinterval(qa.timefinish,'SECOND'),'YYYY-MM-DD HH24:MI:SS') AS fecha,
                                                                          (qa.sumgrades                              *q.grade/q.sumgrades)                                              AS sumgrades
                                                               FROM       m_quiz_attempts qa
                                                               INNER JOIN m_quiz q
                                                               ON         qa.quiz=q.id
                                                               INNER JOIN m_grade_items gt
                                                               ON         gt.itemname=q.NAME
                                                               INNER JOIN m_customfield_data cd
                                                               ON         cd.instanceid=q.course
                                                               WHERE      gt.hidden=0
                                                               AND        cd.value LIKE '%2%'
                                                               AND        cd.fieldid=24)respondido
                                         RIGHT JOIN
                                                    (
                                                               SELECT     count(*) AS intentos,
                                                                          qa.quiz,
                                                                          qa.userid
                                                               FROM       m_quiz_attempts qa
                                                               INNER JOIN m_quiz q
                                                               ON         qa.quiz=q.id
                                                               INNER JOIN m_grade_items gt
                                                               ON         gt.itemname=q.NAME
                                                               INNER JOIN m_customfield_data cd
                                                               ON         cd.instanceid=q.course
                                                               WHERE      gt.hidden=0
                                                               AND        cd.value LIKE '%2%'
                                                               AND        cd.fieldid=24
                                                               GROUP BY   qa.quiz,
                                                                          qa.userid)intentos
                                         ON         intentos.intentos=respondido.attempt
                                         AND        intentos.quiz=respondido.quiz
                                         AND        respondido.userid=intentos.userid)respuesta
                        ON        examenes.quizid=respuesta.quiz
                        AND       examenes.userid=respuesta.userid
                      where examenes.userid= $co
      order by examenes.userid, To_number(examenes.orden),examenes.quizid
        ";
      }
      if($prueba==2){
        $query ="
        SELECT    Row_number() OVER (ORDER BY examenes.userid) AS id,
        examenes.quizid,
        examenes.course,
        examenes.userid,
        examenes.NAME,
        examenes.nombre,
        examenes.desc_puesto,
        examenes.no_cia,
        examenes.no_emple,
        respuesta.fecha,
        respuesta.sumgrades,
        examenes.nombre_rest,
        examenes.codigo_pais,
        examenes.perfil,
        examenes.no_rest,
        examenes.examenes,
        examenes.minima,
        examenes.color,(
        CASE
                  WHEN respuesta.sumgrades>=To_number(examenes.minima) THEN 1
                  ELSE 0
        END) AS ganado
  FROM      (
                 SELECT   *
                 FROM     (
                                     SELECT     q.id AS quizid,
                                                q.NAME,
                                                q.course,
                                                examenes.examenes,
                                                minima.minima,
                                                color.color,orden.orden
                                     FROM       m_quiz q
                                     INNER JOIN m_grade_items gt
                                     ON         gt.itemname=q.NAME
                                     INNER JOIN m_customfield_data cd
                                     ON         cd.instanceid=q.course
                                     INNER JOIN m_course c
                                     ON         q.course=c.id
                                     LEFT JOIN
                                                (
                                                           SELECT     q.course,
                                                                      Count(q.id) AS examenes
                                                           FROM       m_quiz q
                                                           INNER JOIN m_grade_items gt
                                                           ON         gt.itemname=q.NAME
                                                           WHERE      gt.hidden=0
                                                           GROUP BY   q.course)examenes
                                     ON         examenes.course=c.id
                                     LEFT JOIN
                                                (
                                                          SELECT    cd.value AS minima,
                                                                    cd.fieldid,
                                                                    cd.instanceid
                                                          FROM      m_course c
                                                          LEFT JOIN m_customfield_data cd
                                                          ON        c.id=cd.instanceid
                                                          WHERE     cd.fieldid=25)minima
                                     ON         minima.instanceid=c.id
                                     LEFT JOIN
                                    (
                                              SELECT    cd.value AS color,
                                                        cd.fieldid,
                                                        cd.instanceid
                                              FROM      m_course c
                                              LEFT JOIN m_customfield_data cd
                                              ON        c.id=cd.instanceid
                                              WHERE     cd.fieldid=1) color
                                     ON         color.instanceid=c.id
                                     
                                     
                                     
                                     
                                                                        LEFT JOIN
                                     (
                                    SELECT    cd.value AS orden,
                                                        cd.fieldid,
                                                        cd.instanceid
                                              FROM      m_course c
                                              LEFT JOIN m_customfield_data cd
                                              ON        c.id=cd.instanceid
                                              WHERE     cd.fieldid=22) orden
                                     ON         orden.instanceid=c.id
                                     
                                     
                                     
                                     WHERE      gt.hidden=0
                                     AND        c.visible=1
                                     AND        cd.value LIKE '%2%'
                                     AND        cd.fieldid=24)examen,
                          (
                                     SELECT     u.id AS userid,
                                                u.username,
                                                           Concat (e.nombre,concat(' ',e.apellido)) AS nombre,
                                                e.desc_puesto,
                                                e.no_cia,
                                                e.no_emple,
                                                e.nombre_rest,
                                                e.codigo_pais,
                                                e.cod_puesto,
                                                ep.perfil,
                                                e.no_rest
                                     FROM       m_user u
                                     INNER JOIN lea_empleado_mv e
                                     ON         e.no_cia=u.institution
                                     AND        e.no_emple=u.idnumber
                                     INNER JOIN lea_asigna_perfil_mv ep
                                     ON         ep.no_cia=e.no_cia
                                     AND        (
                                                           ep.codigo=e.no_emple
                                                OR         ep.codigo=e.cod_puesto)
                                                WHERE      u.id $params)usuarios
                                                ORDER BY usuarios.userid,
                                                examen.course,
                                                examen.quizid)examenes
                        LEFT JOIN
                              (
                                         SELECT     respondido.*
                                         FROM       (
                                                               SELECT     qa.quiz,
                                                                          qa.userid,
                                                                          qa.attempt,
                                                                          to_char(to_date('1970-01-01','YYYY-MM-DD') + numtodsinterval(qa.timefinish,'SECOND'),'YYYY-MM-DD HH24:MI:SS') AS fecha,
                                                                          (qa.sumgrades                              *q.grade/q.sumgrades)                                              AS sumgrades
                                                               FROM       m_quiz_attempts qa
                                                               INNER JOIN m_quiz q
                                                               ON         qa.quiz=q.id
                                                               INNER JOIN m_grade_items gt
                                                               ON         gt.itemname=q.NAME
                                                               INNER JOIN m_customfield_data cd
                                                               ON         cd.instanceid=q.course
                                                               WHERE      gt.hidden=0
                                                               AND        cd.value LIKE '%2%'
                                                               AND        cd.fieldid=24)respondido
                                         RIGHT JOIN
                                                    (
                                                               SELECT     count(*) AS intentos,
                                                                          qa.quiz,
                                                                          qa.userid
                                                               FROM       m_quiz_attempts qa
                                                               INNER JOIN m_quiz q
                                                               ON         qa.quiz=q.id
                                                               INNER JOIN m_grade_items gt
                                                               ON         gt.itemname=q.NAME
                                                               INNER JOIN m_customfield_data cd
                                                               ON         cd.instanceid=q.course
                                                               WHERE      gt.hidden=0
                                                               AND        cd.value LIKE '%2%'
                                                               AND        cd.fieldid=24
                                                               GROUP BY   qa.quiz,
                                                                          qa.userid)intentos
                                         ON         intentos.intentos=respondido.attempt
                                         AND        intentos.quiz=respondido.quiz
                                         AND        respondido.userid=intentos.userid)respuesta
                        ON        examenes.quizid=respuesta.quiz
                        AND       examenes.userid=respuesta.userid
                      where examenes.userid= $gr
      order by examenes.userid, To_number(examenes.orden),examenes.quizid
        ";
      }
      //echo $restaurante;
      //echo $prueba;
      
      $result  =  $this->getBurnedQuery($query);
      return $result;
    }

  public function listaPuestos($id =null){
      $result =[];
      if($id == null){
        $query = "select * from lea_perfil_mv";
        $result  =  $this->getBurnedQuery($query);
        
      }
      return $result;
  }
  public function listaCO($id = null){
    $result =[];
    if($id == null ){
      $query = "select u.id as userid,u.username, e.nombre,e.apellido,e.desc_puesto,e.no_cia,e.no_emple,e.nombre_rest,e.codigo_pais,e.cod_puesto,ep.tipo,ep.perfil
      FROM m_user u INNER JOIN LEA_EMPLEADO_MV e 
      ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
      inner join lea_asigna_perfil_mv ep
      on ep.no_cia=e.no_cia and  (ep.codigo=e.no_emple or ep.codigo=e.cod_puesto)
      where ep.perfil like '%CO%'";
      $result  =  $this->getBurnedQuery($query);
      return $result;
    }

}
public function listaGR($id =null){
  $result =[];
  if($id = null){
    $query = "select u.id as userid,u.username, e.nombre,e.apellido,e.desc_puesto,e.no_cia,e.no_emple,e.nombre_rest,e.codigo_pais,e.cod_puesto,ep.tipo,ep.perfil
    FROM m_user u INNER JOIN LEA_EMPLEADO_MV e 
    ON e.NO_CIA=u.institution AND e.NO_EMPLE=u.idnumber
    inner join lea_asigna_perfil_mv ep
    on ep.no_cia=e.no_cia and  (ep.codigo=e.no_emple or ep.codigo=e.cod_puesto)
    where ep.perfil like '%GR%'";
    $result  =  $this->getBurnedQuery($query);
  }
  return $result;
}
public function listaRes($id =null){
  $result =[];
  if($id == null){
    $query = "select distinct nombre_rest 
    from lea_empleado_mv";
    $result  =  $this->getBurnedQuery($query);
  }

  return $result;
}



    protected function getBurnedQuery($query){
        global $DB;        
        
        return $DB->get_records_sql($query);
    }
/*
    public function getEmpleadosPorPuestoGerentes()
    {
      global $DB;
  
      $sql = "SELECT u.id, u.username, le.NOMBRE, le.APELLIDO
      FROM {user} u
      INNER JOIN LEA_EMPLEADO_MV le ON le.NO_CIA=u.institution AND le.NO_EMPLE=u.idnumber
      WHERE u.deleted = 0 AND le.status_emp='A' AND ((le.cod_puesto in (
        60004690
        ,27356
        ,5865
        ,60000113
        ,15791
        ,60000056
        ,4789
        ,6990
        ,60000020
        ,8373
        ,11078
        ,11900
        ,11901
        ,30034
        ,11079
        ,11080
        ,11081
        ,24426
        ,60006724
        ,60006851
        ,51
        ,60000055
        ,11082
      ) and le.codigo_pais='SV')
      or
      (le.cod_puesto in (
        60004690
        ,27356
        ,5865
        ,60000113
        ,15791
        ,60000056
        ,4789
        ,6990
        ,60000020
        ,8373
        ,11078
        ,11900
        ,11901
        ,30034
        ,11079
        ,11080
        ,11081
        ,24426
        ,60006724
        ,60006851
        ,60000055
        ,51
        ,11082
      ) and le.codigo_pais='GT')
      or
      (le.cod_puesto in(
        63,
        62,
        99,
        34,
        54,
        56,
        1,
        57,
        83,
        59,
        60006724,
        60006851,
        60000055,
        51,
        64
      )and le.codigo_pais='HN')
      or
  
      (le.cod_puesto in(
        62,
        3,
        34,
        54,
        56,
        1,
        57,
        58,
        59,
        60006724,
        60006851,
        60000055,
        51,
        64
      )and le.codigo_pais='NI'))";
  
      $record = $DB->get_records_sql( $sql );
      return $record;
    }
*/
}

?>