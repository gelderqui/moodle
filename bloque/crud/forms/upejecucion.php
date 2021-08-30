<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css"/>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>
<?php
require_once("{$CFG->libdir}/formslib.php");
require_once('../modelos/presupuesto.php');

class upejecucion extends moodleform {

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



        $response_id = optional_param('response_id', null, PARAM_INT);
        $response_year = optional_param('response_year', null, PARAM_ALPHANUMEXT);
        $response_month = optional_param('response_month', null, PARAM_ALPHANUMEXT);
        $response_idp = optional_param('response_idp', null, PARAM_INT);
        $response_valor = optional_param('response_valor', null, PARAM_NUMBER);

        $mform-> addElement ('select', 'anual', get_string('anual', 'block_inicial'), $years);
        $mform-> setType('anual', PARAM_ALPHANUMEXT);
        $mform-> setDefault('anual', $response_year);

        $mform-> addElement ('select', 'mes', get_string('mes', 'block_inicial'), $meses);
        $mform-> setType('mes', PARAM_ALPHANUMEXT);
        $mform-> setDefault('mes', $response_month);

        $mform-> addElement ('select', 'presupuesto', get_string('presupuesto', 'block_inicial'), $result);
        $mform-> setType('presupuesto', PARAM_INT);
        $mform-> setDefault('presupuesto', $response_idp);

        $mform-> addElement ('text', 'valor', get_string('valor', 'block_inicial'));
        $mform-> setType('valor', PARAM_NUMBER);
        $mform-> setDefault('valor', $response_valor);


        $mform-> addElement ('hidden', 'actuid', get_string('actuid', 'block_inicial'));
        $mform-> setType('actuid', PARAM_ALPHANUMEXT);
        $mform-> setDefault('actuid', $response_id);

        $mform-> addElement ('submit', 'actualizar', get_string('actualizar', 'block_inicial'));


        if(isset($_POST["actualizar"])){
          $response_id = optional_param('actuid', 4, PARAM_INT);
          $registro->id = $respons_id;
          // var_dump($response_id);

        $registro = new stdClass();
        $response_id = optional_param('actuid', 4, PARAM_INT);
        $registro->id = $response_id;
        $registro->years = optional_param('anual', '', PARAM_ALPHANUMEXT);
        $registro->months = optional_param('mes', '', PARAM_ALPHANUMEXT);
        $registro->id_presupuesto = optional_param('presupuesto', '', PARAM_FORMAT);
        $registro->valor = optional_param('valor', '', PARAM_NUMBER);

        $resultado = $DB->update_record('cmiejecucion',$registro, $bulk= false);
        echo '<script>
          swal({
            title: "Success!",
            text: "Ejecución actualizado!",
            type: "success"
          }).then(function() {
            window.location = "http://cursos.mayahonh.com/blocks/inicial/vistas/index_ejecucion.php";
          });
        </script>';
        }
    }
}

?>
