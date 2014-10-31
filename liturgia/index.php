
<html ><head>
<title> Liturgia de $mes </title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<meta http-equiv=\"content-language\" content=\"pt-BR\">

<style>
body{font-size:22px; font-family:arial !important;}
#container {
    width: 800px;
    margin: 0px auto;    
    padding: 10px 0 10px;
    overflow: auto;
   
}

  .mes {
  width: 700px;
        margin: 0 auto;
        padding: 0;
        overflow: auto;        
        list-style-type: none;
        background: #ccc;
} 
	
  .mes .dia-mes{
  	line-height: 1.5em;
border: 1px solid #000;
float: left;
display: inline;
padding: 5px;}

    #nome-dia{background-color: rgb(117, 113, 113);
color: rgb(255, 255, 255);
padding: 11px;}
	
	.titulo-sessoes{background-color: rgb(122, 163, 179);}
	.txt {margin: 15px;
font-size: 18px;}
    #dia-calendario{font-family: arial; border:solid 2px red;
font-size: 11px;}

.destaque
{
	font-size:18px !important;
}

.link-dia{font-size: 12px;}

</style>

</head>
<body>

<?php 

ini_set('max_execution_time', 3600); //300 seconds = 5 minutes

#Inclui o html dom Parser
include('simple_html_dom.php');

$trimestre = '1';

$meses = array( array('janeiro','fevereiro','marco'),
				array('abril','maio','junho'),
				array('julho','agosto','setembro'),
				array('outubro','novembro','dezembro'));

if($_GET['trimestre'] != null)
{
	$trimestre = $_GET["trimestre"];
}


foreach ($meses[(int)$trimestre-1] as $mes) {
	
#Invoca o arquivo que deve ser tratado
$html_mes = file_get_html('http://localhost/ferramentas/liturgia/calendar-'.$mes.'.html');

#Encontra o nome do cliente

$nome = null; 
$pagina_dia = null;
$leitura_do_dia = null;
?>

<div id="container"> <ul class="mes">

<?php 
foreach($html_mes->find('a') as $linkParaODia) {

	$nome = $linkParaODia->innertext;

	if(intval($nome) > 0)
	{	
		echo "<li id=\"dia-".trim($nome)."\" class=\"dia-mes\">";	

		$link_do_dia = "<a href=$linkParaODia->href target=\"_blank\">$linkParaODia->href</a>";

		$html_pagina_dia = file_get_contents($linkParaODia->href);

		$pagina_dia = str_get_html($html_pagina_dia);



		// Buscar Blockquotes
		foreach($pagina_dia->find('table.lit') as $tabela_liturgia) {

			
			
			$nome_do_dia = $tabela_liturgia->find('tbody tr', '2');
			$leitura_do_dia = $tabela_liturgia->find('tbody tr td table', '2');

			if(!empty($nome_do_dia))
			{
				echo "<div id=\"nome-dia\">$nome_do_dia <br/></div><span class=\"link-dia\">Link: $link_do_dia </span>";
			}

			if(!empty($leitura_do_dia))
			{
				echo "<div class=\"titulo-sessoes\">Liturgia Diaria</div>$leitura_do_dia";
			}

		}

		foreach($pagina_dia->find('blockquote') as $oracao_do_dia) 
		{
			echo "<div class=\"titulo-sessoes\">Oracao do dia</div>".$oracao_do_dia->find('p',2);
		}
	echo "</li>"	;
		
	}

} ?>
</ul> </div>

<?php } ?>

</body></html>