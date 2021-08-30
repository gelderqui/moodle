<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css"/>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>
<?php
require_once("{$CFG->libdir}/formslib.php");

class queryEjecucion{

  public function listar(){

    global $DB;

    $sql2 = "SELECT ce.*,
    (case
      WHEN ce.years = 0
        THEN '2021'
      WHEN ce.years = 1
        THEN '2022'
      WHEN ce.years = 2
        THEN '2023'
      WHEN ce.years = 3
        THEN '2024'
      WHEN ce.years = 4
        THEN '2025'
      WHEN ce.years = 5
        THEN '2026'
      WHEN ce.years = 6
        THEN '2027'
      WHEN ce.years = 7
        THEN '2028'
      WHEN ce.years = 8
        THEN '2029'
      WHEN ce.years = 9
        THEN '2030'
        end) as año,
      (case
        WHEN ce.months = 1
          THEN 'Enero'
        WHEN ce.months = 2
          THEN 'Febrero'
        WHEN ce.months = 3
          THEN 'Marzo'
        WHEN ce.months = 4
          THEN 'Abril'
        WHEN ce.months = 5
          THEN 'Mayo'
        WHEN ce.months = 6
          THEN 'Junio'
        WHEN ce.months = 7
          THEN 'Julio'
        WHEN ce.months = 8
          THEN 'Agosto'
        WHEN ce.months = 9
          THEN 'Septiembre'
        WHEN ce.months = 10
          THEN 'Octubre'
        WHEN ce.months = 11
          THEN 'Noviembre'
        WHEN ce.months = 12
          THEN 'Diciembre'
          end) as mes,
            cp.nombre FROM mdl_cmiejecucion ce
        INNER JOIN mdl_cmipresupuesto cp ON cp.id = ce.id_presupuesto
        where ce.condicion = 1";

    $result2 = array();
    if ($datas = $DB->get_records_sql($sql2)) {
      foreach($datas as $data) {
        array_push($result2,(array)$data);
      }
    }
    return $result2;
  }

  public function eliminar(){
    global $DB;


      $registro_update1 = new stdClass();
      $registro_update1->id = optional_param('response_id', null, PARAM_INT);;
      $registro_update1->condicion = 0;
      $resultado1 = $DB->update_record('cmiejecucion',$registro_update1, $bulk= false);
      echo '<script>
      swal({
          title: "Deleted!",
          text: "Ejecución eliminado!",
          type: "warning"
        }).then(function(){
            window.location = "http://cursos.mayahonh.com/blocks/inicial/vistas/index_ejecucion.php";
          });
      </script>';

      // .then(function() {
      //   window.location = "http://cursos.mayahonh.com/blocks/inicial/vistas/index_agrupacion.php";
      // }
  }
}
?>
