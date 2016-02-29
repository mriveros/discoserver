<?php 
session_start();
?>
<?php
//Example FPDF script with PostgreSQL
//Ribamar FS - ribafs@dnocs.gov.br

require('fpdf.php');

class PDF extends FPDF{
function Footer()
{
        
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(343,236,15,236);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,234,'Consulta Generada: '.date('d-M-Y').' '.date('h:i:s'));
}

function Header()
{
   // Select Arial bold 15
        $this->SetFont('Arial','',9);
	$this->Image('img/intn.jpg',15,10,-300,0,'','../../InformeCargos.php');
    // Move to the right
    $this->Cell(80);
    // Framed title
	$this->text(15,32,utf8_decode('Instituto Nacional de Tecnología, Normalización y Metrología'));
	$this->text(315,32,'Sistema Compras y Pagos');
        //$this->text(315,37,'Mes: '.utf8_decode(genMonth_Text($mes).' Año: 2016'));
	//$this->Cell(30,10,'noc',0,0,'C');
    // Line break
    $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(360 ,33,10,33);//largor,ubicacion derecha,inicio,ubicacion izquierda
//table header        
    
    $this->SetFont('Arial','B',8);
    $this->SetTitle('RESUMEN DE COMPRAS');
    $this->Cell(300,5,'UNIDAD DE COMPRAS',100,100,'C');//Titulo
    $this->SetFillColor(153,192,141);
    $this->SetTextColor(255);
    $this->SetDrawColor(153,192,141);
    $this->SetLineWidth(.3);
    /*$this->Cell(20,10,'SIAPE',1,0,'L',1);
    $this->Cell(50,10,'Nome',1,1,'L',1);*/
    
    $this->Cell(25,10,'Item',1,0,'C',1);
    $this->Cell(80,10,'Razon Social',1,0,'C',1);
    $this->Cell(25,10,'Tipo',1,0,'C',1);
    $this->Cell(25,10,'Nro.',1,0,'C',1);
    $this->Cell(30,10,'Fecha',1,0,'C',1);
    $this->Cell(25,10,'Termino',1,0,'C',1);
    $this->Cell(25,10,'Resolucion',1,0,'C',1);
    $this->Cell(30,10,'Estado',1,0,'C',1);
    $this->Cell(30,10,'Monto',1,0,'C',1);
    $this->Cell(30,10,'IVA',1,1,'C',1);


//Restore font and colors


}
}

$pdf=new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
//obtener el nombre de organismo------------------------------------------------
//QUERY and data cargue y se reciben los datos

if  (empty($_POST['txtDesdeFecha'])){$fechadesde=0;}else{$fechadesde=$_POST['txtDesdeFecha'];}
if  (empty($_POST['txtHastaFecha'])){$fechahasta=0;}else{$fechahasta=$_POST['txtHastaFecha'];}

  $mes=substr($fechadesde, 5, 2);
//------------------------------------------------------------------------------      
$pdf->AddPage('L', 'Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);


//Set font and colors




$conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta=pg_exec($conectate,"SELECT 
                    ord.ord_tipo,ord.ord_nro,ord.ord_fecha,ord.ord_termino,ord.ord_res,ord.ord_monto,ord.ord_iva,max(pro.pro_razon) as pro_razon,ord.facturado 
                    from orden_compras ord, proveedores pro
                    where ord.pro_cod=pro.pro_cod
                    and ord.ord_fecha >= '$fechadesde' and ord.ord_fecha<='$fechahasta'  group by ord_cod ");

$numregs=pg_numrows($consulta);
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
//Build table
$fill=false;
$i=0;
while($i<$numregs)
{
    
   
    $tipo=pg_result($consulta,$i,'ord_tipo');
     $razon=pg_result($consulta,$i,'pro_razon');
    $numero=pg_result($consulta,$i,'ord_nro');
    $fecha=pg_result($consulta,$i,'ord_fecha');
    $termino=pg_result($consulta,$i,'ord_termino');
    $resolucion=pg_result($consulta,$i,'ord_res');
     $estado=pg_result($consulta,$i,'facturado');
    $monto=pg_result($consulta,$i,'ord_monto');
    $iva=pg_result($consulta,$i,'ord_iva');
   
  
    
   
     
    $pdf->Cell(25,5,$i+1,1,0,'C',$fill);
    $pdf->Cell(80,5,$razon,1,0,'L',$fill);
    $pdf->Cell(25,5,$tipo,1,0,'C',$fill);
    $pdf->Cell(25,5,$numero,1,0,'L',$fill);
    $pdf->Cell(30,5,$fecha,1,0,'C',$fill);
    if($termino=='1'){$termino='Contado';}else{$termino='Credito';}
    $pdf->Cell(25,5,$termino,1,0,'C',$fill);
    $pdf->Cell(25,5,$resolucion,1,0,'L',$fill);
    if($estado=='t'){$estado='Facturado';}else{$estado='Pendiente';}
    $pdf->Cell(30,5,$estado,1,0,'C',$fill);
    $pdf->Cell(30,5,number_format($monto, 0, '', '.'),1,0,'L',$fill);
    $pdf->Cell(30,5,number_format($iva, 0, '', '.'),1,1,'L',$fill);
    

   
    $fill=!$fill;
    $i++;
}

/*
 * 
 * 
 * Aqui haremos las consultas para los totales
 * 
 * 
 */
$pdf->SetFont('Arial','B',8);
$conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta2=pg_exec($conectate,"SELECT sum (ord.ord_monto) as monto,sum(ord.ord_iva) as iva
                    from orden_compras ord, proveedores pro
                    where ord.pro_cod=pro.pro_cod
                    and ord_fecha >= '$fechadesde' and ord_fecha<='$fechahasta'");
$numregs2=pg_numrows($consulta2);
//Build table
$fill=false;
$i=0;
while($i<$numregs2)
{
    
    
    $montoT=pg_result($consulta2,$i,'monto');
    $ivaT=pg_result($consulta2,$i,'iva');
    
   $pdf->Cell(265,10,'SUMAS TOTALES',1,0,'C',$fill);
    $pdf->Cell(30,10,number_format($montoT, 0, '', '.'),1,0,'C',$fill);
    $pdf->Cell(30,10,number_format($ivaT, 0, '', '.'),1,1,'C',$fill);
    $fill=!$fill;
    $i++;
}






//Add a rectangle, a line, a logo and some text
/*
$pdf->Rect(5,5,170,80);
$pdf->Line(5,90,90,90);
//$pdf->Image('mouse.jpg',185,5,10,0,'JPG','http://www.dnocs.gov.br');
$pdf->SetFillColor(224,235);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,95);
$pdf->Cell(170,5,'PDF gerado via PHP acessando banco de dados - Por Ribamar FS',1,1,'L',1,'mailto:ribafs@dnocs.gov.br');
*/
ob_end_clean();
$pdf->Output();
$pdf->Close();
// generamos los meses 
function genMonth_Text($m) { 
 switch ($m) { 
  case '01': $month_text = "Enero"; break; 
  case '02': $month_text = "Febrero"; break; 
  case '03': $month_text = "Marzo"; break; 
  case '04': $month_text = "Abril"; break; 
  case '05': $month_text = "Mayo"; break; 
  case '06': $month_text = "Junio"; break; 
  case '07': $month_text = "Julio"; break; 
  case '08': $month_text = "Agosto"; break; 
  case '09': $month_text = "Septiembre"; break; 
  case '10': $month_text = "Octubre"; break; 
  case '11': $month_text = "Noviembre"; break; 
  case '12': $month_text = "Diciembre"; break; 
 } 
 return ($month_text); 
} 
?>