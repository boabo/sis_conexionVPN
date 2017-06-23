/***********************************I-SCP-FEA-CVPN-1-22/06/2017****************************************/
CREATE TABLE cvpn.tconexion_vpn (
  id_conexion_vpn SERIAL,
  tipo_empleado VARCHAR(25),
  id_uo_gerencia VARCHAR(50),
  id_funcionario INTEGER,
  nombre_funcionario VARCHAR(70),
  tipo_servicio VARCHAR(30),
  fecha_desde DATE,
  fecha_hasta DATE,
  ip_equipo_remoto VARCHAR(25),
  lista_servicios TEXT,
  nota_adicional TEXT,
  id_proceso_wf INTEGER,
  id_estado_wf INTEGER,
  estado VARCHAR(30),
  nro_tramite VARCHAR(20),
  id_funcionario_contacto INTEGER,
  id_gestion INTEGER,
  CONSTRAINT tconexion_vpn_pkey PRIMARY KEY(id_conexion_vpn),
  CONSTRAINT tconexion_tfuncionario_fk FOREIGN KEY (id_funcionario_contacto)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tconexion_vpn_testado_wf_fk FOREIGN KEY (id_estado_wf)
    REFERENCES wf.testado_wf(id_estado_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tconexion_vpn_tfuncionario_fk FOREIGN KEY (id_funcionario)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tconexion_vpn_tproceso_wf_fk FOREIGN KEY (id_proceso_wf)
    REFERENCES wf.tproceso_wf(id_proceso_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

ALTER TABLE cvpn.tconexion_vpn
  ALTER COLUMN id_conexion_vpn SET STATISTICS 0;

ALTER TABLE cvpn.tconexion_vpn
  ALTER COLUMN id_uo_gerencia SET STATISTICS 0;

ALTER TABLE cvpn.tconexion_vpn
  ALTER COLUMN nombre_funcionario SET STATISTICS 0;

ALTER TABLE cvpn.tconexion_vpn
  ALTER COLUMN fecha_desde SET STATISTICS 0;

ALTER TABLE cvpn.tconexion_vpn
  ALTER COLUMN fecha_hasta SET STATISTICS 0;
/***********************************F-SCP-FEA-CVPN-1-22/06/2017****************************************/