CREATE OR REPLACE FUNCTION cvpn.ft_conexion_vpn_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
    v_id_funcionario	integer;
    v_filtro			varchar ;
	v_bandera_fun		boolean;
    v_estado			varchar;
    v_firma_fun			varchar='';
     v_gerencia			varchar;
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

        	SELECT tf.id_funcionario
            INTO v_id_funcionario
            FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            WHERE tu.id_usuario = p_id_usuario ;

        	IF (v_parametros.tipo_interfaz = 'solicitudConexion')THEN
           		v_filtro = 'cvpn.id_usuario_reg = '||p_id_usuario||' AND ';
            ELSIF (v_parametros.tipo_interfaz = 'voboConexion')THEN
            	v_filtro = '(cvpn.id_usuario_reg = '||p_id_usuario||' OR tew.id_funcionario = '||v_id_funcionario||') AND ';
            END IF;

            IF(p_administrador = 1 OR v_parametros.tipo_interfaz = 'consultaConexion')THEN
            	v_filtro = '0 = 0 AND ';
            END IF;

            WITH RECURSIVE gerencia(id_uo, id_nivel_organizacional, nombre_unidad, nombre_cargo) AS (
              SELECT tu.id_uo, tu.id_nivel_organizacional, tu.nombre_unidad, tu.nombre_cargo
              FROM orga.tuo  tu
              INNER JOIN orga.tuo_funcionario tf ON tf.id_uo = tu.id_uo
              WHERE tf.id_funcionario = v_id_funcionario and tu.estado_reg = 'activo'

              UNION ALL

              SELECT teu.id_uo_padre, tu1.id_nivel_organizacional, tu1.nombre_unidad, tu1.nombre_cargo
              FROM orga.testructura_uo teu
              INNER JOIN gerencia g ON g.id_uo = teu.id_uo_hijo
              INNER JOIN orga.tuo tu1 ON tu1.id_uo = teu.id_uo_padre
              WHERE substring(g.nombre_cargo,1,7) <> 'Gerente'
          	)

            SELECT nombre_unidad
            INTO v_gerencia
            FROM gerencia
            ORDER BY id_nivel_organizacional asc limit 1;

    		--Sentencia de la consulta
			v_consulta:='select
            			cvpn.id_proceso_wf,
                        cvpn.id_estado_wf,
                        cvpn.estado,
                        cvpn.nro_tramite,
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
						--CASE WHEN cvpn.nombre_funcionario = '''' THEN (vfc.nombre_unidad ||'' - ''|| vfc.oficina_nombre)::VARCHAR ELSE (vf.nombre_unidad ||'' - ''|| vf.oficina_nombre)::VARCHAR END AS id_uo_gerencia,
                        '''||v_gerencia||'''::varchar AS id_uo_gerencia,
						cvpn.fecha_reg,
						cvpn.usuario_ai,
						cvpn.id_usuario_reg,
						cvpn.id_usuario_ai,
						cvpn.fecha_mod,
						cvpn.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        COALESCE(vfc.desc_funcionario1, ''''::TEXT) as desc_funcionario1,
                        COALESCE(vf.desc_funcionario1, ''''::TEXT) as fun_contacto,
                        cvpn.id_funcionario_contacto,
                        cvpn.tipo_dispositivo,
                        cvpn.modelo_dispositivo                        
						from cvpn.tconexion_vpn cvpn
						inner join segu.tusuario usu1 on usu1.id_usuario = cvpn.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cvpn.id_usuario_mod

                        inner join wf.testado_wf tew on tew.id_estado_wf = cvpn.id_estado_wf
                        left join orga.vfuncionario_cargo_lugar vfc ON vfc.id_funcionario = cvpn.id_funcionario
                        left join orga.vfuncionario_cargo_lugar vf ON vf.id_funcionario = cvpn.id_funcionario_contacto
				        where  '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice 'v_consulta: %',v_consulta;
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

                        inner join wf.testado_wf tew on tew.id_estado_wf = cvpn.id_estado_wf
                        inner join orga.vfuncionario_cargo_lugar vfc ON vfc.id_funcionario = cvpn.id_funcionario
                        left join orga.vfuncionario_cargo_lugar vf ON vf.id_funcionario = cvpn.id_funcionario_contacto
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'CVPN_RCONEX_VPN_SEL'
 	#DESCRIPCION:	Reporte de una solicitud de Conexion
 	#AUTOR:		franklin.espinoza
 	#FECHA:		05-06-2017 18:34:44
	***********************************/
    elsif(p_transaccion='CVPN_RCONEX_VPN_SEL')then

		begin
        	SELECT tcv.estado
            INTO v_estado
            FROM cvpn.tconexion_vpn tcv
            WHERE tcv.id_proceso_wf = v_parametros.id_proceso_wf;

            IF(v_estado = 'configuracion') THEN
            	SELECT (vfc.desc_funcionario1::varchar ||'-'::varchar|| vfc.nombre_cargo::varchar ||'|'::varchar|| (tew.fecha_reg::date)::varchar)::VARCHAR
            	INTO v_firma_fun
            	FROM cvpn.tconexion_vpn tcv
                INNER JOIN wf.testado_wf tew ON tew.id_estado_wf = tcv.id_estado_wf
            	LEFT JOIN wf.testado_wf tewf ON tewf.id_estado_wf = tew.id_estado_anterior
                left join orga.vfuncionario_cargo vfc ON vfc.id_funcionario = tewf.id_funcionario
            	WHERE tcv.id_proceso_wf = v_parametros.id_proceso_wf;
            ELSIF(v_estado = 'concluido')THEN
            	SELECT  (vfc.desc_funcionario1||'-'||vfc.nombre_cargo ||'|'||tewf.fecha_reg::date)::varchar ||'#'::varchar|| (vfcc.desc_funcionario1||'-'||vfcc.nombre_cargo ||'|'||(tew.fecha_reg::date)::varchar)::VARCHAR
            	INTO v_firma_fun
            	FROM cvpn.tconexion_vpn tcv
            	left JOIN wf.testado_wf tew ON tew.id_estado_wf = tcv.id_estado_wf
                left join orga.vfuncionario_cargo vfcc ON vfcc.id_funcionario = tew.id_funcionario
                left JOIN wf.testado_wf tewf ON tewf.id_estado_wf = tew.id_estado_anterior
                LEFT JOIN wf.testado_wf teg ON teg.id_estado_wf = tewf.id_estado_anterior
                left join orga.vfuncionario_cargo vfc ON vfc.id_funcionario = teg.id_funcionario
                WHERE tcv.id_proceso_wf = v_parametros.id_proceso_wf;
            END IF;


			--Sentencia de la consulta de conteo de registros
            --raise exception 'v_firma_fun_fun :%',v_firma_fun;
			v_consulta:='SELECT distinct on (tcv.nro_tramite)

                        tcv.nro_tramite,
                        tcv.estado,
                        COALESCE('''||v_firma_fun||'''::varchar,''''::varchar) AS fun_firmas,
                        tcv.tipo_empleado,
                        tcv.tipo_servicio,
                        tcv.fecha_desde,
                        tcv.fecha_hasta,
                        tcv.ip_equipo_remoto,
                        tcv.lista_servicios,
                        tcv.nota_adicional,
                        tcv.nombre_funcionario,
                        COALESCE(vfc.desc_funcionario1||''|''::text||vfc.nombre_cargo::text, ''''::TEXT) as desc_fun,
                        COALESCE(vf.desc_funcionario1||''|''::text||vf.nombre_cargo::text, ''''::TEXT) as fun_contacto,
                        CASE WHEN tcv.nombre_funcionario = '''' THEN (vfc.nombre_unidad ||'' - ''|| vfc.oficina_nombre)::VARCHAR ELSE (vf.nombre_unidad ||'' - ''|| vf.oficina_nombre)::VARCHAR END AS ofi_gerencia,
                        tcv.tipo_dispositivo,
                        tcv.modelo_dispositivo                        
                        FROM cvpn.tconexion_vpn tcv
                        left join orga.vfuncionario_cargo_lugar vfc ON vfc.id_funcionario = tcv.id_funcionario
                        left join orga.vfuncionario_cargo_lugar vf ON vf.id_funcionario = tcv.id_funcionario_contacto
                        where tcv.id_proceso_wf = '||v_parametros.id_proceso_wf;

			--raise notice 'v_consulta: %',v_consulta;
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;