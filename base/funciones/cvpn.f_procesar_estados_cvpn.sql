CREATE OR REPLACE FUNCTION cvpn.f_procesar_estados_cvpn (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
DECLARE
    v_nombre_funcion   	 text;
    v_resp    			 varchar;
    v_mensaje 			 varchar;


    v_record_cvpn 			record;




BEGIN

	v_nombre_funcion = 'rec.f_procesar_estados_cvpn';

    select *
    into v_record_cvpn
    from cvpn.tconexion_vpn tcv
    where id_proceso_wf = p_id_proceso_wf;

  	--RAISE EXCEPTION 'CVPN %, %',p_id_estado_wf,p_codigo_estado;

    if(p_codigo_estado = 'aprobacion') then
    	begin
    		update cvpn.tconexion_vpn set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
        end;
    elsif(p_codigo_estado in ('configuracion')) then
    	begin
    		update cvpn.tconexion_vpn set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
        end;

    elsif(p_codigo_estado in ('concluido')) then
    	begin
    		update cvpn.tconexion_vpn set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
   end if;

	return true;

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