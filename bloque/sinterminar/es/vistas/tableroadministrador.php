<?php require_once('../../../config.php');
require_once('../forms/administrador.php');
require_once('../modelos/Querymodelos.php');

 global $DB, $OUTPUT, $PAGE;

$courseid = required_param('courseid', PARAM_INT);

$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_estandarcl', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/estandarcl/vistas/tableroadministrador.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
//$PAGE->set_heading(get_string('edithtml', 'block_estandarcl'));
$settingsnode = $PAGE->settingsnav->add(get_string('estandarclsetting', 'block_estandarcl'));
$editurl = new moodle_url('/blocks/estandarcl/vistas/tableroadministrador.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_estandarcl'), $editurl);
$editnode->make_active();

$actualizateform = new administrador();
?>
<?php
echo $OUTPUT->header();
$actualizateform->display();
?>
<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="../DataTables/Buttons-1.5.6/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="../DataTables/datatables.min.css"/>
<style media="screen">
    hr {width: 100%;
        background:#e5e5e5;
        height: 1px;}
    .col-sm-6 {border-left:#e5e5e5 1px solid;
              border-right:#e5e5e5 1px solid;
              padding: 10px;}
    label{
      color: #13284B;
    }
</style>
<tbody >
  <div class="panel-body" >
    <!--  Formulario -->
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center" >

      <button id="mostrar" class="fitemtitle fitem fitem_fbutton femptylabel clicklistar" align="center">Mostrar</button>

    </div>
<br>
  <hr>
    <!--  Información #13284B-->
    <table id="tbllistado" class="table" cellspacing="0" width="100%">
      <thead>
        <th >No.</th>
        <th class="select-filter">Nombre Completo</th>
        <th class="select-filter">Código</th>
        <th class="select-filter">Departamento</th>
        <th class="select-filter">Curso</th>
        <th class="select-filter">Fecha de inicio de curso</th>
        <th class="select-filter">Fecha de fin de curso</th>
        <th class="select-filter">Avance</th>
        <th class="select-filter">Nota</th>
        <th class="select-filter">Fecha de finalización</th>

    </table>

<div class="row" >
          <div class="col-sm-6">

            <label >Promedio de calificación de areas por curso</label>
            <select id="idcurso1" name="idcurso1" class="form-control selectpicker" data-live-search="true">
            </select>
            <div class="eliminar">
              <canvas id="grafadmin3" ></canvas>
            </div>
          </div>

          <div class="col-sm-6">
            <label>Calificación de curso por estudiante</label>
          <select id="idstudent" name="student" class="form-control selectpicker" data-live-search="true"></select>
            <div class="eliminar2">
              <canvas id="grafadmin4"></canvas>
            </div>
          </div>
        </div>


  </tbody>
  <!--  scripts  -->
  <script src="../DataTables/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
  <script src="../DataTables/dataTables.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/buttons.flash.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/jszip.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/pdfmake.min.js"></script>
  <script src="../DataTables/Buttons-1.5.6/js/vfs_fonts.js"></script>
  <script src="../js/Chart.min.js"></script>
  <script src="../js/Chart.bundle.min.js"></script>
  <script src="../js/chartjs-plugin-datalabels.min.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../js/tableroadmin.js"></script>
  <script src="../js/jspdf.min.js"></script>
  <script type="text/javascript">

  $.noConflict();
  jQuery( document ).ready(function( $ ) {

    jQuery('.clicklistar').click(function() {
      //Fecha de inicio
      var startnew =$("#id_fecha_inicio_day").val();
      var startmonth =$("#id_fecha_inicio_month").val();
      var startyear =$("#id_fecha_inicio_year").val();

      //Fecha de final
      var finishnew =$("#id_fecha_fin_day").val();
      var finishmonth =$("#id_fecha_fin_month").val();
      var finishyear =$("#id_fecha_fin_year").val();

      //Union de fecha
      var fecha_inicio=startyear+'-'+startmonth+'-'+startnew;
      var fecha_fin=finishyear+'-'+finishmonth+'-'+finishnew;

      //variables de formulario

      var codigo=$("#id_codigo").val();
      var curso=$("#id_curso").val();
      var area=$("#id_area").val();
      //Datatable
      var tabla = $("#tbllistado").DataTable(
              {    language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando del 0 al 0 de 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"

                }
              },
              "aProcessing": true,//Activamos el procesamiento del datatables
              "aServerSide": true,//Paginación y filtrado realizados por el servidor
              "scrollX": true,

              dom: 'Bfrtip',//Definimos los elementos del control de tabla
              buttons: [{
                extend: 'excel',
                text: 'Descargar Excel',
                filename:'ReporteUsuarios',
                title:'Reporte de usuarios'
              }
            ],
            "ajax":
            {url: "../ajax/reporte1.php",
            data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,codigo:codigo,curso:curso,area:area},
            type : "get",
            dataType : "json",
            error: function(e){
              console.log(e.responseText);
            }
          },
          "bDestroy": true,
          "iDisplayLength": 50,//Paginación
          "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
        });
  //Graficas de informacion
  jQuery.ajax({
    url: "../ajax/reporte2.php?option=getcurso",
    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,area:area,codigo:codigo,curso:curso},
    type:'get'}).done(
      function(resp)
      {
        function getRandomColor() {
          var letters = '0123456789ABCDEF'.split('');
          var color = '#';
          for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
          }
          return color;
        }
        function getRandomColorEachEmployee(count) {
          var data =[];
          for (var i = 0; i < count; i++) {
            data.push(getRandomColor());
          }
          return data;
        }

        var textSelect = "";
        var textSelect2 = "";
        var textSelect3 = "";

        var valores=JSON.parse(resp);

        textSelect += "<option value='' disabled selected>Seleccione un curso</option>"
        jQuery.each(valores.cursos, function(index, item) {
          textSelect += "<option value='" + item + "' > " + item + "</option>"
        });

        textSelect2 += "<option value='' disabled selected>Seleccione un código de estudiante</option>"
        jQuery.each(valores.estudiantes, function(index, item) {
          textSelect2 += "<option value='" + item + "' > " + item + "</option>"
        });
        textSelect3 += "<option value='' disabled selected>Seleccione una Departamento</option>"
        jQuery.each(valores.tiendasL, function(index, item) {
          textSelect3 += "<option value='" + item + "' > " + item + "</option>"
        });


        jQuery("#idcurso1").html(textSelect);
        jQuery("#idcurso2").html(textSelect);
        jQuery("#idstudent").html(textSelect2);
        jQuery("#idtienda").html(textSelect3);

  $("#idcurso1").change(function(){
          var idcurso1=$("#idcurso1").val();
          // alert(idcurso1);
          jQuery.ajax({
            url: "../ajax/reporte2.php?option=grafica1",
            data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,codigo:codigo,area:area,curso:curso,idcurso1:idcurso1},
            type:'get'}).done(
              function(resp)
              {
                var valores=JSON.parse(resp);

                $('#grafadmin3').remove();
                $('.eliminar').append('<canvas id="grafadmin3" ><canvas>');

                new Chart(document.getElementById("grafadmin3"), {
                  type: 'bar',
                  data: {
                    labels: valores.tiendas,
                    datasets: [
                      {
                        label: "Promedio",
                        backgroundColor: getRandomColorEachEmployee(valores.tiendas.length),
                        data: valores.notas
                      }
                    ]
                  },
                  options: {
                    title: {
                      display: true,
                      text: 'Promedio de areas por curso'
                    },

                    plugins: {
                      datalabels: {

                        color: '#fff',
                      }
                    },
                    scales: {
                      yAxes: [{
                        ticks: {
                          beginAtZero: true
                        }
                      }]
                    }
                  }
                });

              });
            });

  $("#idstudent").change(function(){
                  var idstudent=$("#idstudent").val();

                  jQuery.ajax({
                    url: "../ajax/reporte2.php?option=grafica3",
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,area:area,codigo:codigo,curso:curso,idstudent:idstudent},
                    type:'get'}).done(
                      function(resp)
                      {
                        var valores=JSON.parse(resp);

                        $('#grafadmin4').remove();
                        $('.eliminar2').append('<canvas id="grafadmin4" ><canvas>');

                        new Chart(document.getElementById("grafadmin4"), {
                          type: 'bar',
                          data: {
                            labels:valores.curso,
                            datasets: [
                              {
                                label: "Calificación",
                                backgroundColor: getRandomColorEachEmployee(valores.curso.length),
                                data: valores.notas
                              }
                            ]
                          },
                          options: {
                            title: {
                              display: true,
                              text: 'Calificaciones de usuario'
                            },

                            plugins: {
                              datalabels: {

                                color: '#fff',
                              }
                            },
                            scales: {
                              yAxes: [{
                                ticks: {
                                  beginAtZero: true
                                }
                              }]
                            }
                          }
                        });

                      });
                    });

  });

 })

jQuery('.clicklistar').click();

                  });
</script>

<?php
echo $OUTPUT->footer();
?>
