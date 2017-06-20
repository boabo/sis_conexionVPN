CREATE OR REPLACE FUNCTION "cvpn"."ft_conexion_vpn_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Conexion VPN
 FUNCION: 		cvpn.ft_conexion_vpn_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cvpn.tconexion_vpn'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'cvpn.ft_conexion_vpn_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CVPN_CVPN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	if(p_transaccion='CVPN_CVPN_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cvpn.id_conexion_vpn,
						cvpn.fecha_desde,
						cvpn.tipo_empleado,
						cvpn.ip_equipo_remoto,
						cvpn.fecha_hasta,
						cvpn.estado_reg,
						cvpn.tipo_servicio,
						cvpn.lista_servicios,
						cvpn.id_funcionario,
						cvpn.nota_adicional,
						cvpn.nombre_funcionario,
						cvpn.id_uo_gerencia,
						cvpn.fecha_reg,
						cvpn.usuario_ai,
						cvpn.id_usuario_reg,
						cvpn.id_usuario_ai,
						cvpn.fecha_mod,
						cvpn.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from cvpn.tconexion_vpn cvpn
						inner join segu.tusuario usu1 on usu1.id_usuario = cvpn.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cvpn.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CVPN_CVPN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	elsif(p_transaccion='CVPN_CVPN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_conexion_vpn)
					    from cvpn.tconexion_vpn cvpn
					    inner join segu.tusuario usu1 on usu1.id_usuario = cvpn.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cvpn.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
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
ALTER FUNCTION "cvpn"."ft_conexion_vpn_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
