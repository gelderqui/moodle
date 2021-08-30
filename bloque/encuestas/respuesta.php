<?php
// This file is part of Moodle Course Rollover Plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_report
 * @author      coprlearning
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $DB,$USER;



//Variables que se usaran para dar permisos de admin
$admins = get_admins();
$logueado=$USER->id;
/*La ruta para accededer en la web*/
$PAGE->set_url(new moodle_url('/local/report/encuestas.php'));
/*El contexto es el dominio donde se accede*/
$PAGE->set_context(\context_system::instance());

$PAGE->set_title('Listado de usuarios administrativo');


echo $OUTPUT->header();

//Obnenemos el id de respuesta
$response_id = $_GET["response_id"];
//Los filtros deben ir en minusculas
$filtroadmin='admin';
$filtronombre='nombre';
$filtrocomentario='comentario';
$filtroarea1='tecnica';
$filtroarea2='integral';
$resultado1='Muy competente';
$resultado2='Competente';
$resultado3='Necesita reforzamiento';
$resultado4='No satisfactorio';
$sql=("SELECT @s:=@s + 1 id, encabezado.response_id, encabezado.nomencuesta, encabezado.fecha, encabezado.encargado, encabezado.nombre, encabezado.puesto, encabezado.departamento,format(area1.nota,0) as nota1,format(area2.nota,0) as nota2,format(area1.nota+area2.nota,0) as notafinal,
if(area1.nota+area2.nota>=100,'".$resultado1."',if(area1.nota+area2.nota>= 90,'".$resultado2."',if(area1.nota+area2.nota >= 80,'".$resultado3."','".$resultado4."'))) as resultado,encabezado.comentario
from
(select @s:=0) as s,
(select tempnombre.response_id, tempnombre.nomencuesta, tempnombre.fecha, tempnombre.encargado, tempnombre.nombre, tempnombre.puesto, tempnombre.departamento,tempcomentario.comentario
from
(select tempnombre.response_id, tempnombre.nomencuesta,tempnombre.fecha,tempnombre.encargado,tempnombre.nombre, usuario.department as puesto, usuario.city as departamento
from
(select qrt1.response_id,q.name as nomencuesta, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(qr1.submitted, '%Y-%m-%d %h:%i'),INTERVAL 7 HOUR),'%d/%m/%Y %H:%i') AS fecha,CONCAT(u1.firstname, ' ',u1.lastname) as encargado,qrt1.response as nombre
FROM mdl_user as u1
inner join mdl_questionnaire_response as qr1
on u1.id=qr1.userid
inner join mdl_questionnaire_response_text as qrt1
on qrt1.response_id=qr1.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt1.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) LIKE '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtronombre."%')tempnombre
left JOIN
(select CONCAT(usuario1.firstname, ' ',usuario1.lastname) as nombrecompleto,usuario1.department,usuario1.city
from
mdl_user as usuario1
where usuario1.deleted=0)usuario
on tempnombre.nombre like usuario.nombrecompleto
order by tempnombre.response_id)tempnombre
left join
(select qrt1.response_id,qrt1.response as comentario
from mdl_questionnaire_response_text as qrt1
inner join mdl_questionnaire_question as qq
on qq.id = qrt1.question_id
where lower(qq.name) LIKE '%".$filtrocomentario."%')tempcomentario
on tempnombre.response_id=tempcomentario.response_id)encabezado
left JOIN
(select base.response_id, ((if(suma1.conteo is null , 0,suma1.conteo*70/conteototal.conteo))+(if(suma2.conteo is null , 0,suma2.conteo*65/conteototal.conteo))+(if(suma3.conteo is null , 0,suma3.conteo*55/conteototal.conteo))+(if(suma4.conteo is null , 0,suma4.conteo*50/conteototal.conteo))) as nota
from
(select qr2.id as response_id
from
mdl_questionnaire_response as qr2
inner join mdl_questionnaire_response_rank as qrt2
on qrt2.response_id=qr2.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt2.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) LIKE '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%'
group by qr2.id)base
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%' and qrt.rankvalue=1
group by qr.id) suma1
on base.response_id=suma1.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%' and qrt.rankvalue=2
group by qr.id) suma2
on base.response_id=suma2.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%' and qrt.rankvalue=3
group by qr.id) suma3
on base.response_id=suma3.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%' and qrt.rankvalue=4
group by qr.id) suma4
on base.response_id=suma4.response_id
inner join
(select qr.id as response_id, count(qrt.rankvalue) as conteo
from mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) LIKE '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea1."%'
group by qr.id)conteototal
on base.response_id=conteototal.response_id)area1
on encabezado.response_id=area1.response_id
left JOIN
(select base.response_id, ((if(suma1.conteo is null , 0,suma1.conteo*30/conteototal.conteo))+(if(suma2.conteo is null , 0,suma2.conteo*25/conteototal.conteo))+(if(suma3.conteo is null , 0,suma3.conteo*15/conteototal.conteo))+(if(suma4.conteo is null , 0,suma4.conteo*10/conteototal.conteo))) as nota
from
(select qr2.id as response_id
from
mdl_questionnaire_response as qr2
inner join mdl_questionnaire_response_rank as qrt2
on qrt2.response_id=qr2.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt2.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) LIKE '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%'
group by qr2.id)base
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%' and qrt.rankvalue=1
group by qr.id) suma1
on base.response_id=suma1.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%' and qrt.rankvalue=2
group by qr.id) suma2
on base.response_id=suma2.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%' and qrt.rankvalue=3
group by qr.id) suma3
on base.response_id=suma3.response_id
LEFT join
(select qr.id as response_id,  count(qrt.rankvalue) as conteo
FROM mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) Like '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%' and qrt.rankvalue=4
group by qr.id) suma4
on base.response_id=suma4.response_id
inner join
(select qr.id as response_id, count(qrt.rankvalue) as conteo
from mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrt
on qrt.response_id=qr.id
inner join mdl_questionnaire_question as qq
on qq.id = qrt.question_id
inner join mdl_questionnaire as q
on q.id = qq.surveyid
where lower(q.name) LIKE '%".$filtroadmin."%' and lower(qq.name) LIKE '%".$filtroarea2."%'
group by qr.id)conteototal
on base.response_id=conteototal.response_id)area2
on encabezado.response_id=area2.response_id
where encabezado.response_id=$response_id");

$result = array();
$resultcompleto = array();

if ($datas = $DB->get_records_sql($sql)) {
    foreach($datas as $data) {
        array_push($result,(array)$data);
    }
}

$sql1=("SELECT qqc.content as pregunta,qrr.rankvalue as respuesta
from mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrr
on qr.id=qrr.response_id
inner join mdl_questionnaire_quest_choice as qqc
on qrr.choice_id=qqc.id
inner join mdl_questionnaire_question qq
on qqc.question_id=qq.id
where qr.id=$response_id and qq.name like '%".$filtroarea1."%'");

$result1 = array();

if ($datas1 = $DB->get_records_sql($sql1)) {
    foreach($datas1 as $data1) {
        array_push($result1,(array)$data1);
        array_push($resultcompleto,(array)$data1);
    }
}

$sql2=("SELECT qqc.content as pregunta1,qrr.rankvalue as respuesta1
from mdl_questionnaire_response as qr
inner join mdl_questionnaire_response_rank as qrr
on qr.id=qrr.response_id
inner join mdl_questionnaire_quest_choice as qqc
on qrr.choice_id=qqc.id
inner join mdl_questionnaire_question qq
on qqc.question_id=qq.id
where qr.id=$response_id and qq.name like '%".$filtroarea2."%'");

$result2 = array();

if ($datas2 = $DB->get_records_sql($sql2)) {
    foreach($datas2 as $data2) {
        array_push($result2,(array)$data2);
        array_push($resultcompleto,(array)$data2);
    }
}




$areas=array_merge($result1,$result2);
$completo1=array_merge($result,$areas);
//print_r($result);
$templatecontext = (object)[

    'users' => $result,
    'area1' => $result1,
    'area2' => $result2,
    'completo' => $resultcompleto,
    'completo1' => $completo1,
];

//Se hace la comprobacion si es admin
$bandera=0;
foreach ($admins as $admin)
  {
    if($logueado==$admin->id)
      {
        $bandera=1;
      }
  }
//Si es admin se activa la bandera
if($bandera){
    echo $OUTPUT->render_from_template('local_report/respuesta',$templatecontext);
}else echo "No tiene permisos para ver esto";

    echo $OUTPUT->footer();
