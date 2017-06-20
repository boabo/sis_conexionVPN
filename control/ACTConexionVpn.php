<?php
/**
*@package pXP
*@file ACTConexionVpn.php
*@author  (franklin.espinoza)
*@date 05-06-2017 19:34:44
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
//var_dump(dirname(__FILE__));exit;
require_once(dirname(__FILE__).'/../reportes/RSolConexionPDF.php');

class ACTConexionVpn extends ACTbase{    
			
	function listarConexionVpn(){
		$this->objParam->defecto('ordenacion','id_conexion_vpn');
		$this->objParam->defecto('dir_ordenacion','asc');

		switch($this->objParam->getParametro('pes_estado')) {
			case 'borrador':
				$this->objParam->addFiltro("cvpn.estado in (''borrador'')");
				break;
			case 'aprobacion':
				$this->objParam->addFiltro("cvpn.estado = " . "''aprobacion''");
				break;
			case 'configuracion':
				$this->objParam->addFiltro("cvpn.estado in (''configuracion'')");
				break;
			case 'concluido':
				$this->objParam->addFiltro("cvpn.estado in (''concluido'')");
				break;
			case 'en_proceso':
				$this->objParam->addFiltro("cvpn.estado in (''aprobacion'',''configuracion'')");
				break;
			case 'consulta':
				$this->objParam->addFiltro("cvpn.estado in (''borrador'',''concluido'',''aprobacion'',''configuracion'')");
				break;
		}

		if ($this->objParam->getParametro('estado') != '') {

			$this->objParam->addFiltro("cvpn.estado = ''". $this->objParam->getParametro('estado')."''");

		}

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODConexionVpn','listarConexionVpn');
		} else{
			$this->objFunc=$this->create('MODConexionVpn');
			
			$this->res=$this->objFunc->listarConexionVpn($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarConexionVpn(){
		$this->objFunc=$this->create('MODConexionVpn');	
		if($this->objParam->insertar('id_conexion_vpn')){
			$this->res=$this->objFunc->insertarConexionVpn($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarConexionVpn($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarConexionVpn(){
			$this->objFunc=$this->create('MODConexionVpn');	
		$this->res=$this->objFunc->eliminarConexionVpn($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function cargarDatos(){
        $this->objFunc=$this->create('MODConexionVpn');
        $this->res=$this->objFunc->cargarDatos($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function siguienteEstadoConexion()
    {
        $this->objFunc=$this->create('MODConexionVpn');
        $this->res=$this->objFunc->siguienteEstadoConexion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function anteriorEstadoConexion()
    {
        $this->objFunc=$this->create('MODConexionVpn');
        $this->res=$this->objFunc->anteriorEstadoConexion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

	function reporteSolConexion(){
		$this->objFunc=$this->create('MODConexionVpn');
		$dataSource = $this->objFunc->reporteSolConexion();
		$this->dataSource=$dataSource->getDatos();

		$nombreArchivo = uniqid(md5(session_id()).'[Reporte-Sol. Conexion]').'.pdf';
		$this->objParam->addParametro('orientacion','P');
		$this->objParam->addParametro('tamano','LETTER');
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);

		$this->objReporte = new RSolConexionPDF($this->objParam);

		$this->objReporte->setDatos($this->dataSource);
		$this->objReporte->generarReporte();
		$this->objReporte->output($this->objReporte->url_archivo,'F');
		//$reporte->write(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo);


		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
	}
			
}

?>