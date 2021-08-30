<?php 
namespace block_mcdpde\renders;
class FilterR2{
     protected $sfilters;
     public function __construct(){

     }
     public function getFilters($country, $posicion){

          
          $tmpString = "";
          switch($country) {
               case "GT":
                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Pais</label><select name='' id='mcPais' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Guatemala</option>";
                    $tmpString.="  <option value='sv'>El Salvador</option>";
                    $tmpString.="  <option value='hn'>Honduras</option>";
                    $tmpString.="  <option value='Ni'>Nicaragua</option>";
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";
                    if(($posicion == "RG") || ($posicion == "CE") ||($posicion == "GO") ||($posicion == "GE") ||($posicion == "GC")){
                         $tmpString.="<div class='form-group'>";
                         $tmpString.="<label>Seleccione Gerente Regional</label><select name='' id='mcGR' class='form-select select2'>";
                         $tmpString.="  <option value='gt'>Dato 1</option>";
                         $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                         $tmpString.="</select>";
                         $tmpString.="</div>";
                         $tmpString.="<div class='form-group'>";
                    
                         $tmpString.="<div class='form-group'>";
                         $tmpString.="<label>Seleccione Consultor de Negocios</label><select name='' id='mcCN' class='form-select select2'>";
                         $tmpString.="  <option value='gt'>Dato 1</option>";
                         $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                         $tmpString.="</select>";
                         $tmpString.="</div>";
                         $tmpString.="<div class='form-group'>";
                    }


                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Restaurante </label><select name='' id='mcRE' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Posiciones </label><select name='' id='mcPos' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";
                    echo $tmpString;

                    break;
               case "NI":
                    
                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Pais</label><select name='' id='mcPais' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Guatemala</option>";
                    $tmpString.="  <option value='sv'>El Salvador</option>";
                    $tmpString.="  <option value='hn'>Honduras</option>";
                    $tmpString.="  <option value='Ni'>Nicaragua</option>";
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    // $tmpString.="<div class='form-group'>";
                    // $tmpString.="<label>Seleccione Gerente Regional</label><select name='' id='mcGR' class='form-select select2'>";
                    // $tmpString.="  <option value='gt'>Dato 1</option>";
                    // $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    // $tmpString.="</select>";
                    // $tmpString.="</div>";
                    // $tmpString.="<div class='form-group'>";

                    if(($posicion == "RG") || ($posicion == "CE") ||($posicion == "GO") ||($posicion == "GE") ||($posicion == "GC")){
                         $tmpString.="<div class='form-group'>";
                         $tmpString.="<label>Seleccione Consultor de Negocios</label><select name='' id='mcCN' class='form-select select2'>";
                         $tmpString.="  <option value='gt'>Dato 1</option>";
                         $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                         $tmpString.="</select>";
                         $tmpString.="</div>";
                         $tmpString.="<div class='form-group'>";
                    }

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Restaurante </label><select name='' id='mcRE' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Posiciones </label><select name='' id='mcPos' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";
                    echo $tmpString;
                    $this->sfilters =$tmpString;
                    break;
               case "hn":
                    
                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Pais</label><select name='' id='mcPais' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Guatemala</option>";
                    $tmpString.="  <option value='sv'>El Salvador</option>";
                    $tmpString.="  <option value='hn'>Honduras</option>";
                    $tmpString.="  <option value='Ni'>Nicaragua</option>";
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    // $tmpString.="<div class='form-group'>";
                    // $tmpString.="<label>Seleccione Gerente Regional</label><select name='' id='mcGR' class='form-select select2'>";
                    // $tmpString.="  <option value='gt'>Dato 1</option>";
                    // $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    // $tmpString.="</select>";
                    // $tmpString.="</div>";
                    // $tmpString.="<div class='form-group'>";

                    if(($posicion == "RG") || ($posicion == "CE") ||($posicion == "GO") ||($posicion == "GE") ||($posicion == "GC")){
                         $tmpString.="<div class='form-group'>";
                         $tmpString.="<label>Seleccione Consultor de Negocios</label><select name='' id='mcCN' class='form-select select2'>";
                         $tmpString.="  <option value='gt'>Dato 1</option>";
                         $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                         $tmpString.="</select>";
                         $tmpString.="</div>";
                         $tmpString.="<div class='form-group'>";
                    }
                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Restaurante </label><select name='' id='mcRE' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Posiciones </label><select name='' id='mcPos' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";
                    echo $tmpString;
                    $this->sfilters =$tmpString;
                    break;
               case "sv" :
                    
                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Pais</label><select name='' id='mcPais' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Guatemala</option>";
                    $tmpString.="  <option value='sv'>El Salvador</option>";
                    $tmpString.="  <option value='hn'>Honduras</option>";
                    $tmpString.="  <option value='Ni'>Nicaragua</option>";
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    // $tmpString.="<div class='form-group'>";
                    // $tmpString.="<label>Seleccione Gerente Regional</label><select name='' id='mcGR' class='form-select select2'>";
                    // $tmpString.="  <option value='gt'>Dato 1</option>";
                    // $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    // $tmpString.="</select>";
                    // $tmpString.="</div>";
                    // $tmpString.="<div class='form-group'>";

                    if(($posicion == "RG") || ($posicion == "CE") ||($posicion == "GO") ||($posicion == "GE") ||($posicion == "GC")){
                         $tmpString.="<div class='form-group'>";
                         $tmpString.="<label>Seleccione Consultor de Negocios</label><select name='' id='mcCN' class='form-select select2'>";
                         $tmpString.="  <option value='gt'>Dato 1</option>";
                         $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                         $tmpString.="</select>";
                         $tmpString.="</div>";
                         $tmpString.="<div class='form-group'>";
                    }

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Restaurante </label><select name='' id='mcRE' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    $tmpString.="<div class='form-group'>";
                    $tmpString.="<label>Seleccione Posiciones </label><select name='' id='mcPos' class='form-select select2'>";
                    $tmpString.="  <option value='gt'>Dato 1</option>";
                    $tmpString.="  <option value='sv'>Dato 2</option>";                    ;
                    $tmpString.="</select>";
                    $tmpString.="</div>";
                    $tmpString.="<div class='form-group'>";

                    echo $tmpString;
                    $this->sfilters =$tmpString;
                    break;
                    
          }          
          return $this->sFilters;
     }
}
?>