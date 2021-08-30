<?php

namespace block_mcdpde\models;

class seccionesModel extends mcdpdeModelBase
{/*Primero
  public function configureTest($userid)
  {
    $this->querySelect = ' q.name, (qa.sumgrades*q.grade/q.sumgrades) as sumgrades,u.idnumber,u.institution';
    $this->queryFrom = ' {user} u
    INNER JOIN {quiz_attempts} qa
    ON u.id=qa.userid
    INNER JOIN {quiz} q
    ON qa.quiz=q.id
    left join {grade_items gt
    on gt.courseid=q.course';
    $this->queryWhere = ' u.id = :userid and gt.itemname=q.name and gt.hidden=0';
    $this->queryParams['userid'] = $userid;
  }
  
    public function consulta()
  {
    global $DB;

    return $DB->get_records_sql($this->getSQL(), $this->getQueryParams());

  }
*/
/*public function configureTest($userid)
{
  $this->querySelect = ' examen.name, CASE WHEN respondido.sumgrades is null THEN 0 ELSE respondido.sumgrades END AS sumgrades,respondido.fecha';
  $this->queryFrom = ' (Select q.id, q.name,q.course from  {quiz} q
  inner join  {grade_items} gt on gt.itemname=q.name
  inner join  {customfield_data} cd on cd.instanceid=q.course
  where gt.hidden=0 and cd.value like \'%2%\' and cd.fieldid=21)examen
  left join
  (select respuesta.quiz,respuesta.name,respuesta.fecha,(respuesta.nota*respuesta.grade/respuesta.sumgrades) as sumgrades
  from 
  (Select qa.quiz,qa.attempt,qa.sumgrades as nota,q.name,to_char(to_date(\'01-01-1970\',\'DD-MM-YYYY\') + numtodsinterval(qa.timefinish,\'SECOND\'),\'DD-MM-YYYY\') as fecha,q.grade,q.sumgrades
  from  {quiz_attempts} qa inner join  {quiz} q on qa.quiz=q.id
  inner join  {grade_items} gt on gt.itemname=q.name
  inner join  {customfield_data} cd on cd.instanceid=q.course
  where qa.userid=:userid1 and gt.hidden=0 and cd.value like \'%2%\' and cd.fieldid=21)respuesta
  inner join 
  (Select qa.quiz,count(*) as intentos from  {quiz_attempts} qa
  inner join  {quiz} q on qa.quiz=q.id
  inner join  {grade_items} gt on gt.itemname=q.name
  inner join  {customfield_data} cd on cd.instanceid=q.course
  where qa.userid=:userid2 and gt.hidden=0 and cd.value like \'%2%\' and cd.fieldid=21
  group by qa.quiz)intentos
  on intentos.intentos=respuesta.attempt and intentos.quiz=respuesta.quiz)respondido
  on examen.id=respondido.quiz';
  $this->queryWhere = '1=1';
  $this->queryParams['userid1'] = $userid;
  $this->queryParams['userid2'] = $userid;
}*/
public function configureTest($userid)
{
  $this->querySelect = 'respuesta.quiz,respuesta.name,respuesta.fecha,(respuesta.nota*respuesta.grade/respuesta.sumgrades) as sumgrades';
  $this->queryFrom = ' (Select qa.quiz,qa.attempt,qa.sumgrades as nota,q.name,to_char(to_date(\'01-01-1970\',\'DD-MM-YYYY\') + numtodsinterval(qa.timefinish,\'SECOND\'),\'DD-MM-YYYY\') as fecha,q.grade,q.sumgrades
  from {quiz_attempts} qa inner join {quiz} q on qa.quiz=q.id
  inner join {grade_items} gt on gt.itemname=q.name
  inner join {customfield_data} cd on cd.instanceid=q.course
  where qa.userid=:userid1 and gt.hidden=0 and cd.value like \'%2%\'and cd.fieldid=1)respuesta
  inner join 
  (Select qa.quiz,count(*) as intentos from {quiz_attempts} qa
  inner join {quiz} q on qa.quiz=q.id
  inner join {grade_items} gt on gt.itemname=q.name
  inner join {customfield_data} cd on cd.instanceid=q.course
  where qa.userid=:userid2 and gt.hidden=0 and cd.value like \'%2%\' and cd.fieldid=1
  group by qa.quiz)intentos
  on intentos.intentos=respuesta.attempt and intentos.quiz=respuesta.quiz';
  $this->queryWhere = '1=1';
  $this->queryParams['userid1'] = $userid;
  $this->queryParams['userid2'] = $userid;
}

  public function consulta()
{
  global $DB;

  return $DB->get_records_sql($this->getSQL(), $this->getQueryParams());

}

}

?>
