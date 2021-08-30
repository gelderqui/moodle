<?php
//Incluímos inicialmente la conexión a la base de datos
require_once('../../../config.php');
Class Consultas
{
	 protected $global;

    //Implementamos nuestro constructor
    public function __construct()
    {
       global $DB;
        $this->global = $DB;
    }
//Datos generales
public function reportegeneral($inicio,$fin,$codigo,$curso,$area)
	{
		global $DB,$USER;

      $data = $DB->get_records_sql("SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
															  DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.startdate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as inicio,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.enddate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as final,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(cc.timecompleted ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as completado,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota,
																		CASE when compl.num<=0 or compl.num is null THEN '0%'
																		ELSE concat(cast(((compl.num*100)/act.num)as decimal(16,0)),'%')
																		END AS avance,
																		c.fullname as curso
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid,ue.timestart from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		 u.deleted=0
																		 and u.suspended=0
																		 and u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and usrcourse.timestart>='".$inicio."'
																		 and usrcourse.timestart<='".$fin."'"


				 												 	);
						return $data;
		}

public function grafica4($inicio,$fin,$codigo,$curso,$area)
	{
				global $DB,$USER;

		         $data = $DB->get_records_sql("SELECT department,AVG(nota) as nota from (SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		  u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and c.startdate>='".$inicio."'
																		 and c.enddate<='".$fin."'
																						)as new

																						group by new.department"

																								 );
																				return $data;
																					}

public function reportegrafica($inicio,$fin,$codigo,$curso,$area){
			global $DB,$USER;

				$data = $DB->get_records_sql("SELECT count(case when vista.avance<=0 then 1 else null end )as 'noiniciado',
																				 count(case when vista.avance>=100 then 1 else null end )as 'finalizados'
																					from (
																					SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
															  DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.startdate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as inicio,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.enddate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as final,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(cc.timecompleted ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as completado,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota,
																		CASE when compl.num<=0 or compl.num is null THEN '0%'
																		ELSE concat(cast(((compl.num*100)/act.num)as decimal(16,0)),'%')
																		END AS avance,
																		c.fullname as curso
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		  u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and c.startdate>='".$inicio."'
																		 and c.enddate<='".$fin."'
																					)as vista");
																	return $data;
													}

public function grafica1($inicio,$fin,$codigo,$curso,$area,$idcurso)
	{
		global $DB,$USER;

      $data = $DB->get_records_sql("SELECT department,AVG(nota) as nota from (SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota,
																		c.fullname as curso
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		  u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and c.startdate>='".$inicio."'
																		 and c.enddate<='".$fin."'
																						)as new
																		where curso='$idcurso'
																		group by new.department"

																		 );
																		 return $data;
			}

			public function grafica3($inicio,$fin,$codigo,$curso,$area,$persona)
	{
			global $DB,$USER;

	      $data = $DB->get_records_sql("SELECT curso,nota from (SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
															  DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.startdate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as inicio,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.enddate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as final,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(cc.timecompleted ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as completado,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota,
																		CASE when compl.num<=0 or compl.num is null THEN '0%'
																		ELSE concat(cast(((compl.num*100)/act.num)as decimal(16,0)),'%')
																		END AS avance,
																		c.fullname as curso
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		  u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and c.startdate>='".$inicio."'
																		 and c.enddate<='".$fin."'
																			 )as new
																			where new.codigo like '%".$persona."%'"

																				 );

																						return $data;
																	}
	public function graficaF($inicio,$fin,$codigo,$curso,$area,$variable){
		global $DB,$USER;

			$data = $DB->get_records_sql("SELECT count(case when vista.avance<=0 then 1 else null end )as 'noiniciado',
																		 count(case when vista.avance>=100 then 1 else null end )as 'finalizados',department
																			from (SELECT   @s:=@s+1 id,Concat(u.firstname,' ',u.lastname) as nombre,
																u.username as codigo,
																u.department,
															  DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.startdate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as inicio,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(c.enddate ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as final,
																DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(cc.timecompleted ,'%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i')as completado,
																			CASE When
																		    	notas.finalgrade<=0 or notas.finalgrade is null
																		      THEN
																		        '0'
																				 ELSE
																		   	notas.finalgrade
																			END AS nota,
																		CASE when compl.num<=0 or compl.num is null THEN '0%'
																		ELSE concat(cast(((compl.num*100)/act.num)as decimal(16,0)),'%')
																		END AS avance,
																		c.fullname as curso
																		from (SELECT @s:= 0) AS s,mdl_user u
																		inner join (Select distinct ue.userid, e.courseid from mdl_user_enrolments ue
																		inner join mdl_enrol e
																		on ue.enrolid=e.id
																		) as usrcourse
																		on u.id=usrcourse.userid
																		left join (Select gi.courseid,gg.userid,gg.finalgrade
																		from mdl_grade_items gi
																		inner join mdl_grade_grades gg
																		on gg.itemid=gi.id
																		where gi.itemtype='course')as notas
																		on u.id=notas.userid and usrcourse.courseid=notas.courseid
																		left join mdl_course_completions cc
																		on u.id=cc.userid and usrcourse.courseid=cc.course
																		left join(Select count(id)as num,course from mdl_course_completion_criteria
																		group by course) as act
																		on act.course=usrcourse.courseid
																		left join (Select userid,course,count(id)as num from mdl_course_completion_crit_compl
																		group by course, userid)as compl
																		on compl.userid=u.id and compl.course=usrcourse.courseid
																		left join mdl_course c
																		on usrcourse.courseid = c.id
																		where
																		  u.username like '%".$codigo."%'
																		 and u.department like '%".$area."%'
																		 and c.shortname like '%".$curso."%'
																		 and c.startdate>='".$inicio."'
																		 and c.enddate<='".$fin."')as vista
																	where department like '%".$variable."%'
																	group by department
																	");
														return $data;
											}

				public function cursos(){
				global $DB,$USER;

         		 $data = $DB->get_records_sql("SELECT distinct(shortname),id from mdl_course
											   where shortname is not null  and id<>1 or shortname=''");
						return $data;
			}
				public function areas(){
				global $DB,$USER;

         		 $data = $DB->get_records_sql("SELECT distinct(department)as department from mdl_user where department<>''");
						return $data;
			}

}

?>
