/***********************************I-DAT-FEA-CVPN-1-22/06/2017****************************************/
INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'CVPN', E'Sistema de Conexion VPN', E'2017-06-22', E'CVPN', E'activo', E'conexionVPN', NULL);

select pxp.f_insert_tgui ('<i class="fa fa-link fa-2x"></i> CONEXION VPN', '', 'CVPN', 'si', 1, '', 1, '', '', 'CVPN');

select pxp.f_insert_tgui ('Solicitud de ConexiÃ³n', 'Solicitud de ConexiÃ³n', 'SOLCONEX', 'si', 1, 'sis_conexionVPN/vista/conexion_vpn/RegistroConexionVpn.php', 2, '', 'RegistroConexionVpn', 'CVPN');
select pxp.f_insert_tgui ('VoBo Solicitudes', 'VoBo Solicitudes', 'VOBOSOL', 'si', 2, 'sis_conexionVPN/vista/conexion_vpn/VoBoConexionVpn.php', 2, ' ', 'VoBoConexionVpn', 'CVPN');
select pxp.f_insert_tgui ('Consulta Solicitudes', 'Consulta', 'CON', 'si', 3, 'sis_conexionVPN/vista/conexion_vpn/ConsultaConexionVpn.php', 2, ' ', 'ConsultaConexionVpn', 'CVPN');


/***********************************F-DAT-FEA-CVPN-1-22/06/2017****************************************/