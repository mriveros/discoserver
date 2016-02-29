<?php 
session_start();
?>
<?php
require('./fpdf.php');
class PDF extends FPDF{
    
function Footer()
{
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,280,9,280);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,283,'Datos Generados en: '.date('d-M-Y').' '.date('h:i:s'));
}
function Header()
{
   // Select Arial bold 15
    $this->SetFont('Arial','',16);
    $this->Image('img/intn.jpg',10,14,-300,0,'','../../InformeCargos.php');
    // Move to the right
    $this->Cell(80);
    // Framed title
    $this->text(37,19,utf8_decode('Instituto Nacional de Tecnología, Normalización y Metrología'));
    $this->SetFont('Arial','',8);
    $this->text(37,24,"Avda. Gral. Artigas 3973 c/ Gral Roa- Tel.: (59521)290 160 -Fax: (595921) 290 873 ");
    $this->text(37,34,"Telefax: (595921) 295 408 e-mail: compras@intn.gov.py");
    //-----------------------TRAEMOS LOS DATOS DE CABECERA----------------------
   
    //---------------------------------------------------------
        $this->Ln(30);
        $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,40,10,40);//largor,ubicacion derecha,inicio,ubicacion izquierda
    //------------------------RECIBIMOS LOS VALORES DE POST-----------
    if  (empty($_POST['txtCodigoFactura'])){$codfactura='';}else{ $codfactura = $_POST['txtCodigoFactura'];}
    $conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres")
    or die ('Error al conectar a la base de datos');
    $consulta=pg_exec($conectate,"   select ret.ret_cod,ret.ret_nom,pro.pro_nom||' '||pro.pro_ape as proveedor,
    pro.pro_razon , fac.fac_cod,fac.fac_nro,pagret.pagret_cod,pagret.pagret_fecha,pagret.pagret_monto
    from pago_retenciones pagret,
    facturas fac, 
    retenciones ret,
    proveedores pro
    where pagret.fac_cod=fac.fac_cod 
    and ret.ret_cod=pagret.ret_cod
    and pro.pro_cod=fac.pro_cod
    and pagret.pagret_cod=$codfactura");
    $fecharet=pg_result($consulta,0,'pagret_fecha');
    $monto=pg_result($consulta,0,'pagret_monto');
    $codigo=pg_result($consulta,0,'ret_cod');
    //table header CABECERA        
    $this->SetFont('Arial','B',12);
    $this->SetTitle('UNIDAD DE OPERATIVA DE CONTRATACIONES');
    $this->text(55,50,'Retencion de Facturas');
    $this->text(10,65,'Fecha:');//Titulo
    $this->text(45,65,$fecharet);
    $this->text(10,75,'Monto Retencion:');
    $this->text(50,75,number_format($monto,0,'.','.'));
    $this->text(10,85,'Codigo Retencion:');
    $this->text(50,85,number_format($codigo,0,'.','.'));
    
}
}

$pdf= new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
$pdf->AddPage();
//------------------------RECIBIMOS LOS VALORES DE POST-----------
if  (empty($_POST['txtCodigoFactura'])){$codfactura='';}else{ $codfactura = $_POST['txtCodigoFactura'];}
//-------------------------Damos formato al informe-----------------------------    

$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
   
//----------------------------Build table---------------------------------------
$pdf->SetXY(10,100);
$pdf->Cell(30,10,'Fecha',1,0,'C',50);
$pdf->Cell(30,10,'Monto Retencion',1,0,'C',50);
$pdf->Cell(30,10,'Codigo',1,1,'C',50);
$fill=false;
$i=0;
$pdf->SetFont('Arial','',10);
//------------------------QUERY and data cargue y se reciben los datos-----------
$conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');

    $conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
    $consulta=pg_exec($conectate,"select ret.ret_cod,ret.ret_nom,pro.pro_nom||' '||pro.pro_ape as proveedor,
    pro.pro_razon , fac.fac_cod,fac.fac_nro,pagret.pagret_cod,pagret.pagret_fecha,pagret.pagret_monto
    from pago_retenciones pagret,
    facturas fac, 
    retenciones ret,
    proveedores pro
    where pagret.fac_cod=fac.fac_cod 
    and ret.ret_cod=pagret.ret_cod
    and pro.pro_cod=fac.pro_cod
    and pagret.pagret_cod=$codfactura");
   
    $numregs=pg_numrows($consulta);
while($i<$numregs)
{   
   $fecharet=pg_result($consulta,0,'pagret_fecha');
    $monto=pg_result($consulta,0,'pagret_monto');
    $codigo=pg_result($consulta,0,'ret_cod');
   
   
    $pdf->Cell(30,5,$fecharet,1,0,'C',$fill);
    $pdf->Cell(30,5,number_format($monto,0,'.','.'),1,0,'R',$fill);
    $pdf->Cell(30,5,number_format($codigo,0,'.','.'),1,1,'R',$fill);
    $fill=!$fill;
    $i++;
}
$pdf->Output("Orden Compra_".$codigo,"I");
$pdf->Close();
