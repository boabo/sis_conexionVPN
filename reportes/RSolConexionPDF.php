<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RSolConexionPDF extends  ReportePDF{
    var $datos ;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    function Header() {
        $height = 25;
        $this->ln(5);
        $this->MultiCell(40, $height, '', 1, 'C', 0, '', '');
        //$this->Cell(40, $height, '', 1, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height, "\n" . 'SOLICITUD DE CONEXION VPN', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, $height, "\n" . '', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 15, 36);
    }

    function setDatos($datos) {

        $this->datos = $datos;
        //var_dump( $this->datos);exit;
    }

    function  generarReporte()
    {  // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->AddPage();
        $this->SetMargins(15, 40, 15);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $height = 5;
        $width2 = 5;
        $width3 = 46;

        $nro_tramite = $this->datos[0]['nro_tramite'];
        $tipo_empleado = $this->datos[0]['tipo_empleado'];
        $tipo_servicio = $this->datos[0]['tipo_servicio'];
        $fecha_desde = date_format(date_create($this->datos[0]['fecha_desde']), 'd/m/Y');
        $fecha_hasta = date_format(date_create($this->datos[0]['fecha_hasta']), 'd/m/Y');
        $ip_equipo_remoto = $this->datos[0]['ip_equipo_remoto'];
        $lista_servicios = $this->datos[0]['lista_servicios'];
        $nota_adicional = $this->datos[0]['nota_adicional'];
        $nombre_funcionario = $this->datos[0]['nombre_funcionario'];
        $desc_fun = explode('|',$this->datos[0]['desc_fun']);
        $fun_contacto = explode('|',$this->datos[0]['fun_contacto']);
        $ofi_gerencia = $this->datos[0]['ofi_gerencia'];

        $estado = $this->datos[0]['estado'];
        $fun_firmas = $this->datos[0]['fun_firmas'];

        $this->setY($this->getY()+15);

        $this->SetFont('', 'B',9);
        $this->setTextColor(0,0,0);
        $this->Cell($width3+20, $height, 'N° TRAMITE', 1, 0, 'C', 0,'', 0);
        $this->Cell($width3+4, $height, 'SERVICIO HABILITADO', 1, 0, 'C', 0,'', 0);
        $this->Cell($width2+30, $height, 'FECHA DESDE',  1, 0, 'C', 0,'', 0);
        $this->Cell(35, $height, 'FECHA HASTA',  1, 0, 'C', 0,'', 0);
        $this->Ln();
        $this->SetFont('', '',9);
        $this->Cell($width3+20, $height, $nro_tramite,  1, 0, 'C', 0,'', 0);
        $this->Cell($width3+4, $height, $tipo_servicio,  1, 0, 'C', 0,'', 0);
        $this->Cell($width2+30, $height, $fecha_desde, 1, 0, 'C', 0,'', 0);
        $this->Cell(35, $height, $fecha_hasta,  1, 0, 'C', 0,'', 0);
        $this->Ln();

        $this->setY($this->getY()+5);

        if($estado=='configuracion'){
            $d_fun_aprob = explode('|',$fun_firmas);
            $f_fun_a = $d_fun_aprob[0];
            $f_fecha_a = $d_fun_aprob[1];
        }else if($estado=='concluido'){
            $d_fun_aprob = explode('#',$fun_firmas);
            $f_aprob = explode('|',$d_fun_aprob[0]);
            $f_conf = explode('|',$d_fun_aprob[1]);

            $f_fun_a = $f_aprob[0];
            $f_fecha_a = $f_aprob[1];

            $f_fun_c = $f_conf[0];
            $f_fecha_c = $f_conf[1];

        }

        if($nombre_funcionario == '') {
            $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse; 
   			font-family: "Times New Roman";
   			font-size: 11pt;
		}
		</style>
		<body>
		<table border="1">
        	<tr>
            	<td><b>Gerencia :</b><br>$ofi_gerencia</td> 
            	<td><b>Funcionario Solicitante : </b><br>$desc_fun[0]</td> 
        	</tr>
        	
        	<tr>
        	    <td><b>Tipo Empleado : </b>$tipo_empleado</td> 
        		<td ><b>IP Equipo Remoto :</b>$ip_equipo_remoto</td>
        	</tr>
        	<tr>
            	<td colspan = "2"><b>Servicios a utilizar VPN:</b> $lista_servicios</td> 
        	</tr>
        	<tr>
            	<td colspan = "2"><b>Notas Adicionales :</b> $nota_adicional</td> 
             
        	</tr>
        	
    	</table>
    	</body>
EOF;
        }else{
            $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse; 
   			font-family: "Times New Roman";
   			font-size: 11pt;
		}
		</style>
		<body>
		<table border="1">
        	<tr>
        	    <td><b>Nombre Empleado Ext. :</b><br>$nombre_funcionario</td> 
            	<td><b>Gerencia :</b><br>$ofi_gerencia</td> 
            	<td><b>Funcionario Solicitante : </b><br>$fun_contacto[0]</td> 
        	</tr>
        	
        	<tr rowspan="2">
        	    <td ><b>Tipo Empleado :</b><br>$tipo_empleado</td> 
        		<td ><b>IP Equipo Remoto :</b><br>$ip_equipo_remoto</td>
        	</tr>
        	<tr>
            	<td colspan = "3"><b>Servicios a utilizar VPN:</b> $lista_servicios</td> 
        	</tr>
        	<tr>
            	<td colspan = "3"><b>Notas Adicionales :</b> $nota_adicional</td>     
        	</tr>
        	
    	</table>
    	</body>
EOF;
        }
        $this->writeHTML ($html);
        if($estado != 'borrador') {
            if($nombre_funcionario == ''){
                $nombre_sol = $desc_fun;
            }else{
                $nombre_sol = $fun_contacto;
            }

            if($estado == 'aprobacion'){

                $tbl = '<table>
                <tr>
                <td style="width: 30%"></td>
                <td style="width: 40%">
                <table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Elaborado por:</b> <br>' . $nombre_sol[0]  . '</td>
                    </tr>
                    <tr>
                        <td align="center" > 
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($nombre_sol[0], $nombre_sol[1]) . '" alt="Logo">
                            <br>'.date_format(date_create($f_fecha_a), 'd/m/Y').' - Solicitante
                        </td>
                     </tr>

                </table>
                </td>
                <td style="width:30%;"></td>
                </tr>
                </table>
                
            ';
                $this->Ln(5);
                $this->writeHTML($tbl, true, false, false, false, '');
            }
            else if ($estado == 'configuracion') {

                $tbl = '<table>
                <tr>
                <td style="width: 15%"></td>
                <td style="width: 70%">
                <table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Elaborado por:</b> <br>' . $nombre_sol[0]  . '</td>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Aprobado por:</b><br> ' . explode('-',$f_fun_a)[0] . '</td>
                    </tr>
                    <tr>
                        <td align="center" > 
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($nombre_sol[0], $nombre_sol[1]) . '" alt="Logo">
                            <br>'.date_format(date_create($f_fecha_a), 'd/m/Y').' - Solicitante
                        </td>
                        <td align="center" >
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($d_fun_aprob[0], $d_fun_aprob[1]) . '" alt="Logo">
                            <br>'. date_format(date_create($f_fecha_a), 'd/m/Y').' - Vo.Bo.
                        </td>
                     </tr>
                     <!--<tr>
                        <td>Firma Electrónica</td>    
                        <td>Firma Electrónica</td>    
                     </tr>-->
                </table>
                </td>
                <td style="width:15%;"></td>
                </tr>
                </table>
                
            ';
                $this->Ln(5);
                $this->writeHTML($tbl, true, false, false, false, '');
            } else if($estado == 'concluido'){

                if($nombre_funcionario == ''){
                    $nombre_sol = $desc_fun;
                }else{
                    $nombre_sol = $fun_contacto;
                }
                $tbl = '<table>
                <tr>
                <td style="width: 5%"></td>
                <td style="width: 90%">
                <table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Elaborado por:</b> ' . $nombre_sol[0] . '</td>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Aprobado por:</b> ' . explode('-',$f_fun_a)[0] . '</td>
                        <td style="font-family: Calibri; font-size: 9px;"><b> Configurado por:</b> ' . explode('-',$f_fun_c)[0] . '</td>
                    </tr>
                    <tr>
                        <td align="center" > 
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($nombre_sol[0],$nombre_sol[1]) . '" alt="Logo">
                            <br>'.date_format(date_create($f_fecha_a), 'd/m/Y').' - Solicitante
                        </td>
                        <td align="center" >
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen(explode('-',$f_fun_a)[0], explode('-',$f_fun_a)[1]) . '" alt="Logo">
                            <br>'. date_format(date_create($f_fecha_a), 'd/m/Y').' - Vo.Bo.
                        </td>
                        <td align="center" >
                            <br><br>
                            <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen(explode('-',$f_fun_c)[0], explode('-',$f_fun_c)[1]) . '" alt="Logo">
                            <br>'. date_format(date_create($f_fecha_c), 'd/m/Y').' - Configurado.
                        </td>
                     </tr>
                     <!--<tr>
                        <td>Firma Electrónica</td>    
                        <td>Firma Electrónica</td>    
                     </tr>-->
                </table>
                </td>
                <td style="width:5%;"></td>
                </tr>
                </table>
                
            ';
                $this->Ln(5);
                $this->writeHTML($tbl, true, false, false, false, '');
            }
        }
    }

    function generarImagen($nom, $nac){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$nac ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png"; //$this->objParam->getParametro('nombre_archivo')

        return $url_archivo;
    }

}
?>