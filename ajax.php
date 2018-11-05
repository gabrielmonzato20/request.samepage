<?php 
  
include 'config.php';
include 'funcoes.php';
include 'includes/php/constantes.php';
include 'includes/php/verificaLogin.php';

//PAGINACAO
include ('includes/php/paginacao_header.php' );

//echo 'a:'.$_GET['data1'];
//echo 'b:'.$_POST['numero'];

 if($_GET['data1'] == undefined || $_GET['data2'] == undefined){
    $_GET['data1'] = date('Y-m-d');
    $_GET['data2'] = date('Y-m-d');
}

     $sql_controle = "SELECT DISTINCT date(pc.dt_inclusao_pergunta) AS dt_inclusao_pergunta,
                            (SELECT count(numero_celular) FROM campanha where date(dt_inclusao) = date(c.dt_inclusao) ) AS recebidos,
                            count(DISTINCT pc.numero_celular) AS envios,
                            (SELECT count(numero_celular) FROM pesquisa_controle where resposta <> '' AND date(dt_inclusao_pergunta) = date(pc.dt_inclusao_pergunta) ) AS resposta,
                            (SELECT count(numero_celular) FROM pesquisa_controle pc1 where resposta = '' AND date(dt_inclusao_pergunta) = date(pc.dt_inclusao_pergunta)) AS sem_resposta,
                            count(pc.numero_celular) AS envios_total,
                            (SELECT count(numero_celular) FROM pesquisa_controle where resposta <> '' AND date(dt_inclusao_pergunta) = date(pc.dt_inclusao_pergunta)) AS recebimento_total
                        FROM pesquisa_controle pc JOIN 
                             perguntas p ON p.id = pc.id_pergunta_atual LEFT JOIN
                             perguntas_adicionais pa ON pa.id = pc.id_pergunta_adicional LEFT JOIN
                             campanha c ON c.numero_celular = pc.numero_celular WHERE (DATE(pc.dt_inclusao_pergunta) between '".$_GET['data1']."' AND '".$_GET['data2']."' )  GROUP BY DATE(pc.dt_inclusao_pergunta) LIMIT ".$numreg." OFFSET ".$inicial." ;";
    $result_controle =  mysqli_query($db, $sql_controle);

    $total_linhas = mysqli_num_rows ( $result_controle );  
?>
  </br>
  </br>

<form name="frmMain" action="relatorio.php" method="post">

    <table class="table table-striped table-bordered table-advance table-hover order-culunm" id="sample_1">
        <thead>
            <div class="table-overflow">
                <tr>
                    <th><i class="glyphicon glyphicon-calendar" style="font-size:20px;margin-right: 4px;"></i>Data</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=formatData(mysqli_result ( $result_controle, $i, 'dt_inclusao_pergunta' ),1)?></td>
<?php 
                        }
?>
                </tr>
        </thead>
        <tbody>
            <tr>
                <th><i class="glyphicon glyphicon-log-in"  style="font-size:20px;margin-right: 4px;"></i>Recebidas</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'recebidos' )?></td>
<?php 
                        }
?>            
            </tr>
            <tr>
                <th><i class="glyphicon glyphicon-log-out" style="font-size:20px;margin-right: 4px;"></i>Envios(distintos)</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'envios' )?></td>
<?php 
                        }
?>         
            </tr>
            <tr>
                <th><i class="glyphicon glyphicon-ok" style="margin-right: 4px;"> </i>Resposta(distintos)</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'resposta' )?></td>
<?php 
                        }
?>         
            </tr>
            <tr>
                <th><i class="glyphicon glyphicon-remove"  style="margin-right: 4px;"></i>Sem Resposta</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'sem_resposta' )?></td>
<?php 
                        }
?>         
            </tr>
            <tr>
                <th><i class="glyphicon glyphicon-open" style="margin-right: 4px;"></i>Total Envios</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'envios_total' )?></td>
<?php 
                        }
?>         
            </tr>
            <tr>
                <th><i class="glyphicon glyphicon-save" style="margin-right: 4px;"></i>Total Recebimento</th>
<?php 
                        for ($i = 0; $i < $total_linhas; $i++){
?>
                            <td><?=mysqli_result ( $result_controle, $i, 'recebimento_total' )?></td>
<?php 
                        }
?>         
            </tr>
            </div>
        </tbody>
    </table>
<?php
//PAGINACAO FOOTER
include ('includes/php/paginacao_footer.php' );
?>
</form>
