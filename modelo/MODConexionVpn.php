<?php
/**
*@package pXP
*@file MODConexionVpn.php
*@author  (franklin.espinoza)
*@date 05-06-2017 19:34:44
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODConexionVpn extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarConexionVpn(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cvpn.ft_conexion_vpn_sel';
		$this->transaccion='CVPN_CVPN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('estado','varchar');
		$this->captura('nro_tramite','varchar');
		$this->captura('id_conexion_vpn','int4');
		$this->captura('fecha_desde','date');
		$this->captura('tipo_empleado','varchar');
		$this->captura('ip_equipo_remoto','varchar');
		$this->captura('fecha_hasta','date');
		$this->captura('estado_reg','varchar');
		$this->captura('tipo_servicio','varchar');
		$this->captura('lista_servicios','text');
		$this->captura('id_funcionario','int4');
		$this->captura('nota_adicional','text');
		$this->captura('nombre_funcionario','varchar');
		$this->captura('id_uo_gerencia','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_funcionario1','text');
		$this->captura('fun_contacto','text');
		$this->captura('id_funcionario_contacto','int4');
        $this->captura('tipo_dispositivo','varchar');
        $this->captura('modelo_dispositivo','varchar');        

		$this->setParametro('tipo_interfaz', 'tipo_interfaz', 'varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		//var_dump($this->consulta);exit;
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarConexionVpn(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cvpn.ft_conexion_vpn_ime';
		$this->transaccion='CVPN_CVPN_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion

		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('tipo_empleado','tipo_empleado','varchar');
		$this->setParametro('ip_equipo_remoto','ip_equipo_remoto','varchar');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_servicio','tipo_servicio','varchar');
		$this->setParametro('lista_servicios','lista_servicios','text');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_funcionario_contacto','id_funcionario_contacto','int4');
		$this->setParametro('nota_adicional','nota_adicional','text');
		$this->setParametro('nombre_funcionario','nombre_funcionario','varchar');
		//$this->setParametro('id_uo_gerencia','id_uo_gerencia','varchar');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('estado', 'estado', 'varchar');
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
        $this->setParametro('tipo_dispositivo', 'tipo_dispositivo', 'varchar');        
        $this->setParametro('modelo_dispositivo', 'modelo_dispositivo', 'varchar');                

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarConexionVpn(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cvpn.ft_conexion_vpn_ime';
		$this->transaccion='CVPN_CVPN_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('estado', 'estado', 'varchar');
        $this->setParametro('nro_tramite', 'nro_tramite', 'varchar');
		$this->setParametro('id_conexion_vpn','id_conexion_vpn','int4');
		$this->setParametro('fecha_desde','fecha_desde','date');
		$this->setParametro('tipo_empleado','tipo_empleado','varchar');
		$this->setParametro('ip_equipo_remoto','ip_equipo_remoto','varchar');
		$this->setParametro('fecha_hasta','fecha_hasta','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tipo_servicio','tipo_servicio','varchar');
		$this->setParametro('lista_servicios','lista_servicios','text');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_funcionario_contacto','id_funcionario_contacto','int4');
		$this->setParametro('nota_adicional','nota_adicional','text');
        $this->setParametro('nombre_funcionario','nombre_funcionario','varchar');
        $this->setParametro('tipo_dispositivo', 'tipo_dispositivo', 'varchar');        
        $this->setParametro('modelo_dispositivo', 'modelo_dispositivo', 'varchar');                
		//$this->setParametro('id_uo_gerencia','id_uo_gerencia','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarConexionVpn(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cvpn.ft_conexion_vpn_ime';
		$this->transaccion='CVPN_CVPN_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_conexion_vpn','id_conexion_vpn','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

    function cargarDatos(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='cvpn.ft_conexion_vpn_ime';
        $this->transaccion='CVPN_OFI_GET';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('usuario','usuario','int4');
        $this->captura('v_desc_funcionario','varchar');
        $this->captura('v_id_funcionario','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function siguienteEstadoConexion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'cvpn.ft_conexion_vpn_ime';
        $this->transaccion = 'CVPN_SIGESTCON_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        //$this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');

        /*$this->setParametro('f_actual', 'f_actual', 'timestamp');
        $this->setParametro('nombreVista', 'nombreVista', 'varchar');*/

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }


    function anteriorEstadoConexion()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'cvpn.ft_conexion_vpn_ime';
        $this->transaccion = 'CVPN_ANTECON_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_reclamo', 'id_reclamo', 'int4');
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        //$this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        //$this->setParametro('operacion', 'operacion', 'varchar');

        //$this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        //$this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');



        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
	
	function reporteSolConexion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento = 'cvpn.ft_conexion_vpn_sel';
		$this->transaccion = 'CVPN_RCONEX_VPN_SEL';
		$this->tipo_procedimiento = 'SEL';

		$this->setCount(false);
		$this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');


		$this->captura('nro_tramite', 'varchar');
		$this->captura('estado', 'varchar');
		$this->captura('fun_firmas', 'varchar');
		$this->captura('tipo_empleado', 'varchar');
		$this->captura('tipo_servicio', 'varchar');
		$this->captura('fecha_desde', 'date');
		$this->captura('fecha_hasta', 'date');
		$this->captura('ip_equipo_remoto', 'varchar');
		$this->captura('lista_servicios', 'text');
		$this->captura('nota_adicional', 'text');
		$this->captura('nombre_funcionario', 'varchar');
		$this->captura('desc_fun', 'text');
		$this->captura('fun_contacto', 'text');
        $this->captura('ofi_gerencia', 'varchar');
        $this->captura('tipo_dispositivo', 'varchar');
        $this->captura('modelo_dispositivo', 'varchar');



		//Ejecuta la instruccion
		$this->armarConsulta();
		//var_dump($this->consulta);exit;
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>