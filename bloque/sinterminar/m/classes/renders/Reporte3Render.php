<?php
namespace block_mcdpde\renders;
use block_mcdpde\forms\nameFilter;
use block_mcdpde\models\QueryModel;
use block_mcdpde\forms\reportFilter2;  

require_once("{$CFG->libdir}/formslib.php");
defined('MOODLE_INTERNAL') || die();

class Reporte3Render implements \renderable
{
    

    private $table;

    private $title;
    private $pagesize;
    private $useinitialsbar;

    // public function __construct(\table_sql $table, $title,$pagesize = 10, $useinitialsbar = true, $areaCode=1)
    // {
    //     $this->table = $table;
    //     $this->title = $title;
    //     $this->useinitialsbar = $useinitialsbar;
    // }
    public function __construct()
    {
        // $this->table = $table;
        // $this->title = $title;
        // $this->useinitialsbar = $useinitialsbar;
    }    
    public function display($data,$data2,$data3 =null, $pais, $perfil)
    {


        $filter = new FilterR2();
        $filter->getFilters($pais, $perfil); 
        $model = new QueryModel();         
        $perfilUser = $model->getUserPuesto();         
        $perfil = preg_replace('/\s+/','',$perfilUser->perfil);
        
        echo "<h2>Cursos de HU</h2>";
        // echo '<button  class="btn btn-primary btn-xs" id="exportarExcelN" onclick="exportarExcelv2();">Exportar a Excel</button>';
        // if ( $perfil != 'CH' && $perfil != 'GR') {
        //     echo '<button  class="btn btn-primary btn-xs" id="exportarExcelN" onclick="exportarExcelv2();">Exportar a Excel</button>';         
        // }
        // if ( $perfil != 'CH' && $perfil != 'GR') {
        echo '<button  class="btn btn-primary btn-xs" id="exportarExcelN" onclick="exportarExcelv2();">Exportar a Excel</button>';         
        // }
        echo "<div role='region' aria-labelledby='caption' tabindex='0' id='tablas1' 'class = 'tableContainer'>";
        echo '<table cellspacing="0" cellpadding="1" class="table">';
        // echo '    <thead class="theaid d-dark">';
        // echo '        <tr>';        
        // echo '        <th scope="col" class="thDark">Codigo</th>';
        // echo '        <th scope="col" class="thDark">Nombre</th>';
        // echo '        <th scope="col" class="thDark">Posicion</th>';
        // foreach($data as $it){
        //     echo "<th class='thDark' scope='col' colspan='".$it->examenes."'>".$it->fullname."</th>";
        // }
        // echo '        </tr>';
        // echo '    </thead>';
        echo '    <thead class="thead-dark">';
        echo '        <tr>';        
        echo '        <th scope="col">Nombre</th>';
        echo '        <th scope="col">Código</th>';
        echo '        <th scope="col">Posición</th>';
        $cont =0;
        $con = 0;
        $keys = array_keys($data2);
        for($i =0; $i<count($data2);$i++){
            $res =$cont%2;
            $class = ($res ==1)?"thYellow":"thRed";            
            // echo "<th scope='col' class='$class' style='background-color:".$data2[$keys[$i]]->color."'><div class='mcUpText'> ".$data2[$keys[$i]]->name."</div></th>";
            echo "<th scope='col' class='$class' style='background-color:".$data2[$keys[$i]]->color."'><div class='mcUpText'  style='color:".$data2[$keys[$i]]->colorletra."' > ".$data2[$keys[$i]]->name."</div></th>";
            $cont++;
            $con++;
            if($con==$data2[$keys[$i]]->examenes){
                echo "<th scope='col' ><div class='mcUpText'> Avance </div></th>";
                $con=0;
            }
            
        }
        // foreach($data2 as $it){
        //     $res =$cont%2;
        //     echo "<th scope='col' class='$class'><div class='mcUpText'> ".$it->name."</div></th>";
        //     $cont++;
        // }

        // foreach($data2 as $it){
        //     $res =$cont%2;
        //     $class = ($res ==1)?"thYellow":"thRed";
        //     echo "<th scope='col' class='$class'><div class='mcUpText'> ".$it->name."</div></th>";
        //     $cont++;
        // }
        
        echo '        </tr>';        
        echo '    </thead>';
       // echo '    <tbody>';
        echo '<div style="height:400px overflow="auto" o >   <tbody>';
        $tid=0;
        foreach($data3 as  $it){
            $narray = $it;
            $limit= count($narray);
            echo "<tr id='tr-$tid'>";
            for($i=0;$i<$limit;$i++){
                $tmp11 =    $narray[$i]; 
                $tmp22 = explode("||",$narray[$i]);   
                $lor = ($i<3)?"tal":"tar";
                if(($i>2) and ($tmp22[0] <$tmp22[1]) and($tmp22[0] !="--")){          
                    echo "<td><span class='mcprom mdmcRed $lor'>".$tmp22[0]."</span></td>";                 
                }
                else{
                    if($i==0){
                        echo "<th><span class='mcprom $lor'>".$tmp22[0]."</span></th>";                 
                    }
                    else{
                        echo "<td><span class='mcprom $lor'>".$tmp22[0]."</span></td>";                 
                    }
                    
                    // echo "<td><span class='mcprom'>".$tmp22[0]."</span></td>";
                }
                $tid++;                     
            }
            echo "</tr>";
        }
        /*
        foreach($data3 as  $it){
            $narray = $it;
            $limit= count($narray);
            echo "<tr id='tr-$tid'>";
            
           /* for($i=0;$i<$limit;$i++){
                $tmp11 =    $narray[$i];
                if(is_numeric($tmp11)){
                    echo "<td><span class='mcprom mdmcRed>".$narray[$i]."</span>'</td>";
                }
                else{
                    echo "<td><span class='mcprom>".$narray[$i]."</span>'</td>";
                }
                $tid++;                     
            }
            for($i=0;$i<$limit;$i++){                
                echo "<td >".$narray[$i]."</td>";
                $tid++;                     
            }
            echo "</tr>";
        }*/
        echo '    </tbody>';
        echo '    </table></div>';
        //echo '    </table>';
        echo "</div>";

        // echo "<pre>";        
        // print_r($data3);
        // echo "</pre>";

 
    }
    
    public function datosIndividuales($idusuario)
    {
        echo '<form action="seccionesimprimir.php" method="POST">
        <input type="hidden" name="userid" value="'.$idusuario.'">
        <input type="submit" value="Generar PDF"/>';
    }

    /**
     * Set the value of Title.
     *
     * @param string title
     *
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value of Pagesize.
     *
     * @param int pagesize
     *
     * @return self
     */
    public function setPagesize(int $pagesize)
    {
        $this->pagesize = $pagesize;

        return $this;
    }

    /**
     * Set the value of Useinitialsbar.
     *
     * @param bool useinitialsbar
     *
     * @return self
     */
    public function setUseinitialsbar(boolean $useinitialsbar)
    {
        $this->useinitialsbar = $useinitialsbar;

        return $this;
    }
}


?>
<style>
.table {
    width: auto;
}
.mcUpText {
    transform: rotate(-90deg);
    color:#000;
}
.tableContainer{
    border: 1px dotted gray;
    height: 350px;
    width: 900px;
    margin: 0 auto;
    display: block;
    overflow: scroll;
}
.table thead th {
    vertical-align: bottom;
    /* border: 1px dotted blue; */
    /* border-top-color: blue;
    border-top-style: dotted; */
    border-top-width: 1px;
    padding-bottom: 25px;
    padding-top: 25px;
    
}
#tablas1{
    height:500px;
}
.thDark {
    background-color: #333;
    color: #fff;
}
/* .thRed {
    background-color: silver;
    color: gray;
}
.thYellow {
    background-color: gray;
    color: silver;
} */
#tr-0{
    display: none;
}
.mdmcRed{
    color:red;
}
table {
  /* font-family: 'Fraunces', serif;
  font-size: 125%;
  white-space: nowrap; */
  margin: 0;
  border: none;
  border-collapse: separate;
  border-spacing: 0;
  table-layout: fixed;
  border: 1px solid black;
}
table td,
table th {
  border: 1px solid black;
  padding: 0.5rem 1rem;
}
table thead th {
  padding: 3px;
  position: sticky;
  top: 0;
  z-index: 1;
  width: 25vw;
  background: white;
}
table td {
  background: #fff;
  padding: 4px 5px;
  text-align: center;
}

table tbody th {
  font-weight: 100;
  font-style: italic;
  text-align: left;
  position: relative;
}
table thead th:first-child {
  position: sticky;
  left: 0;
  z-index: 2;
}
table tbody th {
  position: sticky;
  left: 0;
  background: white;
  z-index: 1;
}
caption {
  text-align: left;
  padding: 0.25rem;
  position: sticky;
  left: 0;
}

[role='region'][aria-labelledby][tabindex] {
  width: 100%;
  max-height: 98vh;
  overflow: auto;
}
[role='region'][aria-labelledby][tabindex]:focus {
  box-shadow: 0 0 0.5em rgba(0, 0, 0, 0.5);
  outline: 0;
}
.mcprom{
    text-align: left;
}
.tal{
    text-align: left!important;
    display: inline-block;
    /* border: 1px solid gray; */
    width: 100%;
}
.tar{
    text-align: right !important;
    display: inline-block;
    /* border: 1px solid gray; */
    width: 100%;
}
.table thead th {
    vertical-align: bottom;

    border-top-style: dotted;
    border-top-width: 1px;
    padding-bottom: 25px;
    padding-top: 25px;
    height: 75px;
    text-align: center;
}
.mcUpText {
    transform: rotate(-90deg);
    display: inline-block;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/shim.min.js" integrity="sha512-nPnkC29R0sikt0ieZaAkk28Ib7Y1Dz7IqePgELH30NnSi1DzG4x+envJAOHz8ZSAveLXAHTR3ai2E9DZUsT8pQ==" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js" integrity="sha512-NerWxp37F9TtBS1k1cr2TjyC9c8Qh6ghgqVBOYXaahgnBkVT6a8KVbO05Z8+LnIIom4CJSSQTZ3VbL396scK5w==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#region-main').removeClass();
    function exportarExcelv2() {
    

        var workbook = XLSX.utils.book_new();
    

        var ws1 = XLSX.utils.table_to_sheet(document.getElementById('tablas1'));
        XLSX.utils.book_append_sheet(workbook, ws1, "Curriculo");
    

        XLSX.writeFile(workbook, "Curriculo.xlsx");
    }
</script>