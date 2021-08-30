<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css"/>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>
<?php
require_once("{$CFG->libdir}/formslib.php");
// require_once('../modelos/Querymodelos.php');


class ejecucion extends moodleform {

    function definition() {


        $mform =& $this->_form;
        global $DB;

        $years = [];
        $year = date('Y');
        $lcont = 0;
        while($lcont<10){
          $years[$lcont] = $year+$lcont;
          $lcont++;
        }

        $meses = [];
        $mes = date('M');
        $lcont = 1;
        while($lcont<=12){
          $meses[$lcont] = $mes+$lcont;
          $lcont++;
        }

        $sql = "SELECT id, nombre FROM mdl_cmipresupuesto where condicion = 1";
        $result = array();
        $datas = $DB->get_records_sql($sql);
        foreach($datas as $data){
          $result[$data->id] = $data->nombre;
        }
        // echo '<pre>';
        // echo print_r($result);
        // echo  '<pre>';

        $mform-> addElement ('select', 'anual', get_string('anual', 'block_inicial'), $years);
        $mform-> setDefault('anual', 'default value');
        $mform-> setType('anual', PARAM_ALPHANUMEXT);

        $mform-> addElement ('select', 'mes', get_string('mes', 'block_inicial'), $meses);
        $mform-> setDefault('mes', 'default value');
        $mform-> setType('mes', PARAM_ALPHANUMEXT);

        $mform-> addElement ('select', 'presupuesto', get_string('presupuesto', 'block_inicial'), $result);
        $mform-> setDefault('presupuesto', 'default value');
        $mform-> setType('presupuesto', PARAM_ALPHA);

        $mform-> addElement ('text', 'valor', get_string('valor', 'block_inicial'));

        $mform-> addElement ('submit', 'guardar',  get_string('guardar', 'block_inicial'));


        if(isset($_POST["guardar"])){
          $registro = new stdClass();
          $registro->years = optional_param('anual', '', PARAM_ALPHANUMEXT);
          $registro->months = optional_param('mes', '', PARAM_ALPHANUMEXT);
          $registro->id_presupuesto = optional_param('presupuesto', '', PARAM_INT);
          $registro->valor = optional_param('valor', '', PARAM_NUMBER);
          $resultado = $DB-> insert_record('cmiejecucion',$registro);
         echo '<script>
          swal({
            title: "Success!",
            text: "Ejecución creada!",
            type: "success"
            }).then(function() {
            window.location = "http://cursos.mayahonh.com/blocks/inicial/vistas/index_ejecucion.php";
            });
          </script>';
      }
    }
}







?>
