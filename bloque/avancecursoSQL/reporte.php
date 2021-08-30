
<?php
 
require_once(__DIR__ . '/../../config.php');
global $DB,$USER;

//Variables que se usaran para dar permisos de admin
$admins = get_admins();
$logueado=$USER->id;
/*La ruta para accededer en la web*/
$PAGE->set_url(new moodle_url('/local/report/reporte.php'));
/*El contexto es el dominio donde se accede*/
$PAGE->set_context(\context_system::instance());

$PAGE->set_title('Listado de cursos teminados');


echo $OUTPUT->header();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label for="">Usuario</label>
  <input type="text" name="codigo" value="<?php if(isset($_POST['codigo'])){ echo $_POST['codigo'];} ?>"> 
  <label for="">Departamento</label>
   <input type="text" name="area" value="<?php if(isset($_POST['area'])){ echo $_POST['area'];} ?>">
   <label for="">Curso</label> 
   <input type="text" name="curso" value="<?php if(isset($_POST['curso'])){ echo $_POST['curso'];} ?>">
   <br>
   <label for="">Fecha Inicio</label> 
   <input type="date" name="inicio" value="<?php if(isset($_POST['inicio'])){ echo $_POST['inicio'];} ?>">
   <label for="">Fecha Fin</label>
   <input type="date" name="fin" value="<?php if(isset($_POST['fin'])){ echo $_POST['fin'];} ?>">
   <input type="submit" value="Mostrar" name="submit">
   <br><br>
   </form>
</body>
<br><br>
</html>

<?php
//if(isset($_POST['submit'])){


  $codigo=$_POST['codigo'];
  $area=$_POST['area'];
  $curso=$_POST['curso'];
  $inicio1=$_POST['inicio'];
  $fin1=$_POST['fin'];
  $inicio=strtotime($inicio1);
  $fin=strtotime($fin1);



    $sql=("SELECT ROW_NUMBER() OVER(ORDER BY u.id) AS id, Concat(u.firstname,' ',u.lastname) as nombre,
    u.username as codigo,
    u.department,
    dateadd(s,c.startdate,'1970-01-01 00:00:00:000') as inicio,
    dateadd(s,c.enddate,'1970-01-01 00:00:00:000')as final,
    dateadd(s,cc.timecompleted,'1970-01-01 00:00:00:000')as completado,
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
        from mdl_user u
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
			   and usrcourse.timestart<='".$fin."'
        ");
?>
<?php
    $result = array();

    if ($datas = $DB->get_records_sql($sql)) {
        foreach($datas as $data) {
            array_push($result,(array)$data);
        }
      }



//print_r($result);
$templatecontext = (object)[

    'users' => $result,
];


$bandera=0;
//Se hace la comprobacion si es admin
foreach ($admins as $admin)
  {
    if($logueado==$admin->id)
      {
        $bandera=1;
      }
  }

//Si es admin se activa la bandera
if($bandera){
    echo $OUTPUT->render_from_template('local_report/usuarios',$templatecontext);
}else echo "No tiene permisos para ver esto";

    echo $OUTPUT->footer();
 //}