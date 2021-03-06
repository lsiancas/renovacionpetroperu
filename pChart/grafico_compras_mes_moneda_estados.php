<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php

 /* Example13: A 2D exploded pie graph */

 $mes = $_GET['cmbmes'];

 $moneda = $_GET['cmbmoneda'];

 // Standard inclusions   

 include_once("pChart/pData.class");

 include_once("pChart/pChart.class");



 //Cantidad Segun Estado

 $sql = "select count(c.idcompra), e.estado from compras c join proveedores pv on c.idproveedor=pv.idproveedor join monedas m on c.idmoneda=m.idmoneda join estados e on c.idestado=e.idestado

join tipocomprobantes tc on c.idtipocomprobante=tc.idtipocomprobante where c.idmoneda='$moneda' and c.fecha_emision like '%-$mes-%' group by e.estado order by c.fecha_emision desc, c.numcomprobante desc";

 $rsql = mysql_query($sql, $link) or die ("Error $sql".mysql_error());

   $total = array();

   $estado = array(); 

   $i = 0;

  

  $reg = mysql_num_rows($rsql);

  

  while($fila = mysql_fetch_array($rsql))

  {

	$total[$i] = $fila[0];

	$estado[$i] = $fila[1];

	$i = $i + 1;

  }

  

  // Dataset definition 

 $DataSet = new pData;

 //$DataSet->AddPoint(array(10,2,3),"Serie1");

 //$DataSet->AddPoint(array("Jan","Feb",$idp),"Serie2");

 

 switch($reg){

	 case 1:

			$DataSet->AddPoint(array($total[0]),"Serie1");

 			$DataSet->AddPoint(array($estado[0]),"Estados");

 		 	break;

	 case 2:

			$DataSet->AddPoint(array($total[0], $total[1]),"Serie1");

 			$DataSet->AddPoint(array($estado[0], $estado[1]),"Estados");

 	        break;

	 case 3:

	 		$DataSet->AddPoint(array($total[0], $total[1], $total[2]),"Serie1");

            $DataSet->AddPoint(array($estado[0], $estado[1], $estado[2]),"Estados");

			break;

  }

 

 

 //$DataSet->AddPoint(array($total[0], $total[1], $total[2], $total[3]),"Serie1");

 //$DataSet->AddPoint(array($estado[0], $estado[1], $estado[2], $estado[3]),"Estados");

 $DataSet->AddAllSeries();

 

 $DataSet->SetAbsciseLabelSerie("Serie1");

 $DataSet->SetAbsciseLabelSerie("Estados");



 // Initialise the graph

 $Test = new pChart(320,180);

 $Test->setFontProperties("/pChart/Fonts/tahoma.ttf",9);

 $Test->drawFilledRoundedRectangle(7,7,350,220,5,240,240,240);

 $Test->drawRoundedRectangle(5,5,340,215,5,230,230,230);



 // Draw the pie chart

 $Test->AntialiasQuality = 0;

 $Test->setShadowProperties(2,2,200,200,200);

 $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),120,100,60,PIE_PERCENTAGE,8);

 $Test->clearShadow();

 $Test->drawPieLegend(210,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 $Test->Render("pChart/grafico_compras_mes_moneda_estados.png");



?>