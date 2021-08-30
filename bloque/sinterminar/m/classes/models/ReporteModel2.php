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
        where cd.value like '%1%' and cd.fieldid=24
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
        where gt.hidden=0 and c.visible=1 and cd.value like '%1%' and cd.fieldid=24
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
                                   AND        cd.value LIKE '%1%'
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
                                              WHERE      u.id IN(5406,5417,5437,5441,5445,6082,6647,6655,6658,6664,6668,6672,7296,7303,5461,6096,6104,6126,6685,6689,6702,7328,7330,7333,7339,7346,7351,6136,6159,6160,6169,6171,6721,6722,6727,6731,6733,6750,7400,7402,5526,5565,6777,7421,7436,7443,7444,5575,5589,5593,5597,6225,6241,6243,6797,6799,6802,6804,6816,6832,7451,7452,7471,7482,7491,5609,5627,5645,6256,6259,6276,6278,6853,6854,7501,7503,5653,5657,5662,5679,5683,6294,6302,6304,6312,6892,7535,7542,7543,7557,5707,6351,6358,6944,6945,6949,6381,6969,6976,5081,5082,5102,5786,5789,6395,6397,6400,6408,6418,6998,7007,7021,7024,7028,7033,7037,5187,5815,5822,5835,5844,5846,6443,6446,7048,7049,5229,5237,5852,5853,5860,5864,5867,5889,6490,6496,6497,7091,7108,7111,5897,5909,5911,5931,6522,6524,7131,7134,7137,7149,5945,5946,5949,5955,5970,6570,6584,7168,7169,7176,7178,7179,7182,7189,5334,5344,5351,5360,5973,5976,5980,5983,5985,5988,5997,5999,6000,6004,6010,6601,7217,5364,5386,5394,5401,5404,6029,8446,8451,8460,7930,7932,7933,7940,8707,7943,7949,7952,7981,7982,8842,8855,8856,8862,7984,8895,8944,8947,8950,8962,8977,8028,8048,9187,9378,8092,8096,8103,8105,9389,9411,9054,9057,9059,9064,8107,8119,8130,8135,9433,9437,9463,9075,9084,9100,8156,8158,8163,8182,9129,9131,9148,9279,9291,9295,9300,9308,8209,8210,9314,9322,8382,8387,8388,8389,7573,7588,7590,7593,7612,8237,8254,8256,8257,7617,7623,7638,7639,7644,8258,8262,8273,8276,8281,8285,8288,8291,7663,8322,7702,7705,7720,8341,8483,8491,7743,7750,7751,7761,7795,7797,7805,7811,7817,8545,8558,8565,8570,7823,7824,7825,7839,7840,7842,7854,8577,8579,8586,5121,7892,7893,7894,13204,14095,13733,14672,14845,15137,15195,15196,14855,15342,11761,15653,14051,13651,12946,12950,12951,11864,12129,12961,12964,12970,12979,12982,12997,12672,9864,9874,9884,10214,10560,10589,10930,10957,10969,11026,11036,11051,11058,11070,11088,11176,11179,11215,11220,11245,11287,11295,11298,11330,11342,11362,11643,11869,11413,11498,11518,11541,11572,11574,11589,9910,10258,10604,9560,9569,9576,10293,10316,10644,10646,10661,10670,9591,10009,10328,10333,10337,10354,10356,10360,10361,10702,9638,9657,10045,10367,10371,10389,9667,9687,10073,10082,10419,10761,10093,10108,9752,9769,10507,10171,10883,10891,10894,10898,10899,9847,9849,20457,18241,18696,18838,20491,18957,22119,18960,18996,22456,19084,19087,18915,19154,19175,20877,19085,19088,19180,19372,19387,19344,16274,19405,16940,16823,19509,19835,17682,17778,25979,17756,18817,16392,17456,16737,16748,19158,19198,17032,18784,18785,18787,19249,19029,19406,19227,19252,18113,31996,32118,33478,31036,37583,38155,33863,33882,34208,34482,35222,36319))usuarios
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
                                                             AND        cd.value LIKE '%1%'
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
                                                             AND        cd.value LIKE '%1%'
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
                                     AND        cd.value LIKE '%1%'
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
                                                               AND        cd.value LIKE '%1%'
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
                                                               AND        cd.value LIKE '%1%'
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
                                     AND        cd.value LIKE '%1%'
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
                                                               AND        cd.value LIKE '%1%'
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
                                                               AND        cd.value LIKE '%1%'
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