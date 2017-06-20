CREATE OR REPLACE FUNCTION "cvpn"."ft_conexion_vpn_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Conexion VPN
 FUNCION: 		cvpn.ft_conexion_vpn_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cvpn.tconexion_vpn'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        05-06-2017 19:34:44
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_conexion_vpn	integer;
			    
BEGIN

    v_nombre_funcion = 'cvpn.ft_conexion_vpn_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CVPN_CVPN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	if(p_transaccion='CVPN_CVPN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into cvpn.tconexion_vpn(
			fecha_desde,
			tipo_empleado,
			ip_equipo_remoto,
			fecha_hasta,
			estado_reg,
			tipo_servicio,
			lista_servicios,
			id_funcionario,
			nota_adicional,
			nombre_funcionario,
			id_uo_gerencia,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.fecha_desde,
			v_parametros.tipo_empleado,
			v_parametros.ip_equipo_remoto,
			v_parametros.fecha_hasta,
			'activo',
			v_parametros.tipo_servicio,
			v_parametros.lista_servicios,
			v_parametros.id_funcionario,
			v_parametros.nota_adicional,
			v_parametros.nombre_funcionario,
			v_parametros.id_uo_gerencia,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_conexion_vpn into v_id_conexion_vpn;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conexion VPN almacenado(a) con exito (id_conexion_vpn'||v_id_conexion_vpn||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conexion_vpn',v_id_conexion_vpn::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CVPN_CVPN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	elsif(p_transaccion='CVPN_CVPN_MOD')then

		begin
			--Sentencia de la modificacion
			update cvpn.tconexion_vpn set
			fecha_desde = v_parametros.fecha_desde,
			tipo_empleado = v_parametros.tipo_empleado,
			ip_equipo_remoto = v_parametros.ip_equipo_remoto,
			fecha_hasta = v_parametros.fecha_hasta,
			tipo_servicio = v_parametros.tipo_servicio,
			lista_servicios = v_parametros.lista_servicios,
			id_funcionario = v_parametros.id_funcionario,
			nota_adicional = v_parametros.nota_adicional,
			nombre_funcionario = v_parametros.nombre_funcionario,
			id_uo_gerencia = v_parametros.id_uo_gerencia,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_conexion_vpn=v_parametros.id_conexion_vpn;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conexion VPN modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conexion_vpn',v_parametros.id_conexion_vpn::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CVPN_CVPN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	elsif(p_transaccion='CVPN_CVPN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from cvpn.tconexion_vpn
            where id_conexion_vpn=v_parametros.id_conexion_vpn;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Conexion VPN eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conexion_vpn',v_parametros.id_conexion_vpn::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "cvpn"."ft_conexion_vpn_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
