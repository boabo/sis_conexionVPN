<?php
/**
 *@package pXP
 *@file PendienteRespuesta.php
 *@author  (Franklin Espinoza)
 *@date 17-10-2016 14:45
 *@Interface para el proceso de Respuesta a un Reclamo.
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConsultaConexionVpn = {
        require:'../../../sis_conexionVPN/vista/conexion_vpn/ConexionVpn.php',
        requireclase:'Phx.vista.ConexionVpn',
        title:'ConsultaConexionVpn',
        nombreVista: 'consultaConexion',
        bnew:false,
        bdel:false,
        tam_pag:50,
        bedit: false,
        constructor: function(config) {
            /*this.tbarItems = ['-',
                this.cmbEstado,'-'

            ];*/
            Phx.vista.ConsultaConexionVpn.superclass.constructor.call(this,config);
            this.store.baseParams.pes_estado = 'consulta';
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //this.cmbEstado.setValue(reg.ROOT.datos.id_gestion);
            //this.cmbEstado.setRawValue('concluido');
            //this.store.baseParams.estado = 'concluido';
            //primera carga
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('sig_estado').setVisible(false);
            //this.getBoton('btnChequeoDocumentosWf').setVisible(false);
            //this.getBoton('btnObs').setVisible(false);

            //this.cmbEstado.on('select',this.capturarEventos, this);
        }/*,
        capturarEventos: function () {
            //if(this.validarFiltros()){
            //this.capturaFiltros();
            //}
            this.store.baseParams.estado=this.cmbEstado.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
        },
        cmbEstado: new Ext.form.ComboBox({
            name: 'estado',
            id: 'estado',
            fieldLabel: 'Estado',
            allowBlank: true,
            emptyText:'Estado...',
            blankText: 'Estado',
            mode:'local',
            store:[ 'borrador', 'aprobacion', 'configuracion','concluido'],
            typeAhead:true,
            forceSelection: true,
            triggerAction:'all',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            hidden:false,
            width:100
        })*/

    };
</script>