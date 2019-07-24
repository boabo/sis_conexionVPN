CREATE OR REPLACE FUNCTION cvpn.ft_conexion_vpn_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
    v_gerencia				varchar;

    --var wf
    v_nro_tramite 			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado			varchar;
	v_id_gestion			integer;
    v_id_funcionario		integer;

    --ANT, SIG
    v_operacion				varchar;
    v_registros_cvpn		record;
    v_id_tipo_estado		integer;
    v_id_usuario_reg		integer;
    v_id_depto				integer;
    v_id_estado_wf_ant		integer;
    v_id_estado_actual		integer;

    v_acceso_directo		varchar;
    v_clase					varchar;
    v_parametros_ad			varchar;
    v_tipo_noti				varchar;
    v_titulo				varchar;

    v_pedir_obs				varchar;
    v_codigo_estado_siguiente varchar;
    v_obs					varchar;

    v_id_funcionario_sol	integer;
    v_nombre_fun  			varchar;
    v_record				record;

    v_nombre_ger		varchar;
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

        	--Gestion para WF
    	   SELECT g.id_gestion
           INTO v_id_gestion
           FROM param.tgestion g
           WHERE g.gestion = EXTRACT(YEAR FROM current_date);

           --Id funcionario para WF
           SELECT tf.id_funcionario
           INTO v_id_funcionario
           FROM segu.tusuario tu
           INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
           WHERE tu.id_usuario = p_id_usuario ;

           --El Funcionario que hace la solicitud o Funcionarion Contacto
           IF(v_parametros.id_funcionario::VARCHAR<>'')THEN
           	v_id_funcionario_sol = v_parametros.id_funcionario;
            v_nombre_fun = '';
           ELSE
           	v_id_funcionario_sol = v_parametros.id_funcionario_contacto;
            v_nombre_fun = v_parametros.nombre_funcionario;
           END IF;

        	 -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_id_gestion,
                 'CVPN',
                 v_id_funcionario,
                 null,
                 'SISTEMA CONEXION VPN',
                 'CVPN'
            );

            --Buscamos gerencia del funcionario


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
			--id_uo_gerencia,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod,
            id_funcionario_contacto,
            id_proceso_wf,
            id_estado_wf,
            estado,
            nro_tramite
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
			v_nombre_fun,
			--v_parametros.id_uo_gerencia,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null,
            v_parametros.id_funcionario_contacto,
            v_id_proceso_wf,
            v_id_estado_wf,
            v_codigo_estado,
            v_nro_tramite

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
        	--El Funcionario que hace la solicitud o Funcionarion Contacto
            IF(v_parametros.id_funcionario::VARCHAR<>'')THEN
             --v_id_funcionario_sol = v_parametros.id_funcionario;
             v_nombre_fun = '';
            ELSE
             --v_id_funcionario_sol = v_parametros.id_funcionario_contacto;
             v_nombre_fun = v_parametros.nombre_funcionario;
            END IF;
			--Sentencia de la modificacion
			update cvpn.tconexion_vpn set
			fecha_desde = v_parametros.fecha_desde,
			tipo_empleado = v_parametros.tipo_empleado,
			ip_equipo_remoto = v_parametros.ip_equipo_remoto,
			fecha_hasta = v_parametros.fecha_hasta,
			tipo_servicio = v_parametros.tipo_servicio,
			lista_servicios = v_parametros.lista_servicios,
			id_funcionario = v_parametros.id_funcionario,
            id_funcionario_contacto = v_parametros.id_funcionario_contacto,
			nota_adicional = v_parametros.nota_adicional,
			nombre_funcionario = v_nombre_fun,
			--id_uo_gerencia = v_parametros.id_uo_gerencia,
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

	/*********************************
 	#TRANSACCION:  'CVPN_ANTECON_IME'
 	#DESCRIPCION:	Anterior estado de una Solicitud de Conexion
 	#AUTOR:		fea
 	#FECHA:		06-06-2017 17:32:59
	***********************************/
    elseif(p_transaccion='CVPN_ANTECON_IME') then
    	begin

        	v_operacion = 'anterior';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

            --obtenemos los datos del registro de solicitud VPN
            select
                tcv.id_conexion_vpn,
                tcv.id_proceso_wf,
                tcv.estado,
                pwf.id_tipo_proceso
            into v_registros_cvpn
            from cvpn.tconexion_vpn  tcv
            inner  join wf.tproceso_wf pwf  on  pwf.id_proceso_wf = tcv.id_proceso_wf
            where tcv.id_proceso_wf  = v_parametros.id_proceso_wf;

            --v_id_proceso_wf = v_registros_cvpn.id_proceso_wf;


            IF  v_operacion = 'anterior' THEN
                --------------------------------------------------
                --Retrocede al estado inmediatamente anterior
                -------------------------------------------------
               	--recuperaq estado anterior segun Log del WF
                  SELECT

                     ps_id_tipo_estado,
                     ps_id_funcionario,
                     ps_id_usuario_reg,
                     ps_id_depto,
                     ps_codigo_estado,
                     ps_id_estado_wf_ant
                  into
                     v_id_tipo_estado,
                     v_id_funcionario,
                     v_id_usuario_reg,
                     v_id_depto,
                     v_codigo_estado,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);

                  select
                    ew.id_proceso_wf
                  into
                    v_id_proceso_wf
                  from wf.testado_wf ew
                  where ew.id_estado_wf= v_id_estado_wf_ant;
            END IF;


			 v_acceso_directo = '../../../sis_conexionVPN/vista/conexion_vpn/VoBoConexionVpn.php';
             v_clase = 'VoBoConexionVpn';
             v_parametros_ad = '{filtro_directo:{campo:"cvpn.id_proceso_wf",valor:"'||v_id_proceso_wf::varchar||'"}}';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Bueno';

              -- registra nuevo estado

              v_id_estado_actual = wf.f_registra_estado_wf(
                    v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                    v_id_funcionario,                --  funcionario del estado anterior
                    v_parametros.id_estado_wf,       --  estado actual ...
                    v_id_proceso_wf,                 --  id del proceso actual
                    p_id_usuario,                    -- usuario que registra
                    v_parametros._id_usuario_ai,
                    v_parametros._nombre_usuario_ai,
                    v_id_depto,                       --depto del estado anterior
                    '[RETROCESO] '|| v_parametros.obs,
                    v_acceso_directo,
                    v_clase,
                    v_parametros_ad,
                    v_tipo_noti,
                    v_titulo);

                IF  not cvpn.f_ant_estado_cvpn_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                   raise exception 'Error al retroceder estado';

                END IF;

                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo volvio al anterior estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;

        end;

    /*********************************
 	#TRANSACCION:  'CVPN_SIGESTCON_IME'
 	#DESCRIPCION:	Siguiente estado de una Solicitud de Conexion
 	#AUTOR:		fea
 	#FECHA:		06-06-2017 17:32:59
	***********************************/
    elseif(p_transaccion='CVPN_SIGESTCON_IME') then
    	begin

          --recupera el registro de la CVPN
          select tcv.*
          into v_registros_cvpn
          from cvpn.tconexion_vpn tcv
          where id_proceso_wf = v_parametros.id_proceso_wf_act;

          SELECT
            ew.id_tipo_estado ,
            te.pedir_obs,
            ew.id_estado_wf
           into
            v_id_tipo_estado,
            v_pedir_obs,
            v_id_estado_wf
          FROM wf.testado_wf ew
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ew.id_tipo_estado
          WHERE ew.id_estado_wf =  v_parametros.id_estado_wf_act;

           -- obtener datos tipo estado siguiente
           select te.codigo into
             v_codigo_estado_siguiente
           from wf.ttipo_estado te
           where te.id_tipo_estado = v_parametros.id_tipo_estado;


           IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN
           	 v_id_depto = v_parametros.id_depto_wf;
           END IF;

           IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
           	 v_obs = v_parametros.obs;
           ELSE
           	 v_obs='---';
           END IF;

             --configurar acceso directo para la alarma
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Bueno';


             IF   v_codigo_estado_siguiente not in('borrador')   THEN

                  v_acceso_directo = '../../../sis_conexionVPN/vista/conexion_vpn/ConexionVpn.php';
             	  v_clase = 'ConexionVpn';
                  v_parametros_ad = '{filtro_directo:{campo:"cvpn.id_proceso_wf",valor:"'||v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;


             -- hay que recuperar el supervidor que seria el estado inmediato...
            	v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                             v_parametros.id_funcionario_wf,
                                                             v_parametros.id_estado_wf_act,
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_registros_cvpn.nro_tramite,'--')||' Obs:'||v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);



         		IF cvpn.f_procesar_estados_cvpn(p_id_usuario,
           									v_parametros._id_usuario_ai,
                                            v_parametros._nombre_usuario_ai,
                                            v_id_estado_actual,
                                            v_parametros.id_proceso_wf_act,
                                            v_codigo_estado_siguiente) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';

          		END IF;


          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado del Reclamo)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);

          -- Devuelve la respuesta
          return v_resp;
        end;
    /*********************************
 	#TRANSACCION:  'CVPN_CVPN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		05-06-2017 19:34:44
	***********************************/

	elsif(p_transaccion='CVPN_OFI_GET')then

		begin
			--Sentencia de la eliminacion
			/*SELECT tcv.nombre_unidad
            INTO v_gerencia
            FROM orga.vfuncionario_cargo_lugar tcv
            WHERE tcv.id_funcionario = v_parametros.id_funcionario;  */

            SELECT vfcl.id_funcionario, vfcl.desc_funcionario1
            INTO v_record
			FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario;
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Gerencia del Funcionario');
            v_resp = pxp.f_agrega_clave(v_resp,'v_desc_funcionario',v_record.desc_funcionario1::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_funcionario',v_record.id_funcionario::varchar);

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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;