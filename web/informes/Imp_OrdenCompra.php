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
    $this->text(37,29,"UNIDAD DE OPERATIVA DE CONTRATACIONES");
    $this->text(37,34,"Telefax: (595921) 295 408 e-mail: compras@intn.gov.py");
    //-----------------------TRAEMOS LOS DATOS DE CABECERA----------------------
   
    //---------------------------------------------------------
        $this->Ln(30);
        $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,40,10,40);//largor,ubicacion derecha,inicio,ubicacion izquierda
    //------------------------RECIBIMOS LOS VALORES DE POST-----------
    
    if  (empty($_POST['txtOrdenImprimir'])){$nrofactura='';}else{ $nrofactura = $_POST['txtOrdenImprimir'];}
    $conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
    $consulta=pg_exec($conectate,"select pro_razon,ord.ord_monto,ord.ord_iva 
    from orden_compras ord,proveedores pro 
    where  ord.pro_cod=pro.pro_cod and ord.ord_nro='$nrofactura'");
    $proveedor=pg_result($consulta,0,'pro_razon');
    $monto=pg_result($consulta,0,'ord_monto');
    $iva=pg_result($consulta,0,'ord_iva');
    //table header CABECERA        
    $this->SetFont('Arial','B',12);
    $this->SetTitle('UNIDAD DE OPERATIVA DE CONTRATACIONES');
    $this->text(55,50,'ORDEN DE COMPRAS');
    $this->text(10,65,'Proveedor:');//Titulo
    $this->text(45,65,$proveedor);
    $this->text(10,75,'Monto Total:');
    $this->text(45,75,number_format($monto,0,'.','.'));
    $this->text(10,85,'IVA:');
    $this->text(45,85,number_format($iva,0,'.','.'));
    
}
}

$pdf= new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
$pdf->AddPage();
//------------------------RECIBIMOS LOS VALORES DE POST-----------
if  (empty($_POST['txtOrdenImprimir'])){$nrofactura='';}else{ $nrofactura = $_POST['txtOrdenImprimir'];}
//-------------------------Damos formato al informe-----------------------------    

$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
   
//----------------------------Build table---------------------------------------
$pdf->SetXY(10,100);
$pdf->Cell(90,10,'Producto',1,0,'C',50);
$pdf->Cell(25,10,'Cantidad',1,0,'C',50);
$pdf->Cell(25,10,'Precio',1,0,'C',50);
$pdf->Cell(25,10,'Subtotal',1,1,'C',50);
$fill=false;
$i=0;
$pdf->SetFont('Arial','',10);

//------------------------QUERY and data cargue y se reciben los datos-----------
$conectate=pg_connect("host=localhost port=5432 dbname=disco user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta=pg_exec($conectate,"select pro.pro_nom,comdet_cant,comdet_precio,comdet_subtotal
from orden_compras ord,compras_detalles comdet,productos pro 
where  pro.pro_cod=pro.pro_cod and 
ord.ord_cod=comdet.ord_cod and
ord.ord_nro='$nrofactura'");
$numregs=pg_numrows($consulta);
while($i<$numregs)
{   
    $producto=pg_result($consulta,$i,'pro_nom');
    $cantidad=pg_result($consulta,$i,'comdet_cant');
    $precio=pg_result($consulta,$i,'comdet_precio');
    $subtotal=pg_result($consulta,$i,'comdet_subtotal');
   
    $pdf->Cell(90,5,$producto,1,0,'L',$fill);
    $pdf->Cell(25,5,number_format($cantidad,0,'.','.'),1,0,'C',$fill);
    $pdf->Cell(25,5,number_format($precio,0,'.','.'),1,0,'R',$fill);
    $pdf->Cell(25,5,number_format($subtotal,0,'.','.'),1,1,'R',$fill);
    $fill=!$fill;
    $i++;
}
$pdf->Output("Orden Compra_".$nrofactura,"I");
$pdf->Close();
