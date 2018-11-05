<?php
$ROOT='';

date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include 'config.php';
include 'funcoes.php';
include 'includes/php/constantes.php';
include 'includes/php/verificaLogin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Relatorio</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <style>
table, td, th {    
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 10px;
}

body {
    margin: 0;
    margin-top:    20px;
    margin-right:  20px;
    margin-bottom: 20px;
    margin-left:   20px;
}

.test + .tooltip.right > .tooltip-arrow {
  border-right: 5px solid black;
  z-index: 100; 
}
#menu li{
  display:inline-block;
}
div#formulario input{
	display:inline-block;
}
</style>
</head>
<div style="margin:0px auto;width: 150px;">
   </i><img src="logo.png" style="height: 50px;" ></div>
<div class"container-fluid">
  <p><?='<b>Usuário:</b> '.$_SESSION['usuario']['nome']?></p>
  <div class="row">

  <div class="col-md-10">
<?php
    //if ( $_SESSION['usuario']['admin'] ) {
?>
      <!--a href="configuracao" class="btn btn-info"><span class="glyphicon glyphicon-cog"></span>   Configuração</a-->
<?php
    //}
?>
      <!--a href="cadastrar_numero"><button type="button" class="btn btn-info">Cadastrar/Reiniciar Número (Pesquisa)</button></a-->
      <ul id='menu'>
      <li><a href="whats_busca.php"><button type="button" class="btn btn-info">Listar Números</button></a></li>
      <li><a href="#"><button type="button" class="btn btn-info">Relatorio</li>
      </button>
  </ul>
  </div>
  <div class="col-md-2" align="right"><a href="logout.php" class="btn btn-info"><span class="glyphicon glyphicon-log-out"></span>Sair</a></div>
  
  </div>
  
  <hr></hr>
</div>
<div id='formulario'>
	<form action="relatorio.php" method="post">
		<label for='dt1'>Data inicial: </label><input type="date" id='dt1' name="datainicial" value="2018-11-01" >
		<label for='dt2'>Data Final:   </label><input type="date" id='dt2' name="datafinal"   value="<?= date('Y-m-d'); ?>" >
		<button type='submit' class="btn btn-primary" id='buscar2'>Pesquisar</button>
	<button onclick="exportTableToExcel('sample_1')" class='btn btn-primary'>Exportar</button>
	</form>
	
</div>
<div id="divisao_refresh2">
</div>
<!--<table class="table table-striped table-bordered table-advance table-hover
                                             order-culunm" id="sample_1">
                                                <thead>
                                                      <div class="table-overflow">
                                                       <tr>
                                                        <th><i  class="fa fa-calendar" style="font-size:20px;margin-right: 4px;"></i>Data</th>code hear</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    <tr>
                                                        <th><i class="icon-envelope-letter"  style="font-size:20px;margin-right: 4px;"></i>Recebidas</th></tr>
                                                        <tr>
                                                        <th><i class=""></i>Envios(distintos)</th></tr>
                                                        <tr>
                                                        <th><i class="glyphicon glyphicon-ok" style="margin-right: 4px;"> </i>Resposta(distintos)</th></tr>
                                                        <tr>
                                                        <th><i class="glyphicon glyphicon-remove"  style="margin-right: 4px;"></i>Sem Resposta</th></tr>
                                                        <tr>
                                                        <th><i class="glyphicon glyphicon-open" style="margin-right: 4px;"></i>Total Envios</th></tr>
                                                        <tr>
                                                        <th><i class="glyphicon glyphicon-save" style="margin-right: 4px;"></i>Total Recebimento</th>
                                                        </tr>
                                                    </div>
                                                </tbody>
                                            </table> -->
<?php
$datainicial = $_POST['datainicial'];
?>
<script type="text/javascript">
$("#buscar2").click(function(){
    comeca();
});

$(document).ready(function(){
        comeca();
      })
      var timerI = null;
      var timerR = false;
 
      function para(){
          if(timerR)
              clearTimeout(timerI)
          timerR = false;
      }
      function comeca(){
          para();
          lista();
      }
      function lista(){
        $.ajax({
          url:"atualiza2.php",
          data: {
              data1: "<?=( $_POST['datainicial'] != "" ? $datainicial : undefined )?>",
              data2: "<?=( $_POST['datafinal'] != "" ? $_POST['datafinal'] : undefined )?>"
            },
            success: function (textStatus){
            $('#divisao_refresh2').html(textStatus);
            //console.log('foi!'); //mostrando resultado
          }
        })
        timerI = setTimeout("lista()", 4000);//tempo de espera
                  timerR = true;
 
      }

</script>
                                            <script type="text/javascript">
    function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}</script>
</body>

</html>
