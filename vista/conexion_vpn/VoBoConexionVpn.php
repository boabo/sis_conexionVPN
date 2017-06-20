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
    Phx.vista.VoBoConexionVpn = {
        require:'../../../sis_conexionVPN/vista/conexion_vpn/ConexionVpn.php',
        requireclase:'Phx.vista.ConexionVpn',
        title:'VoBoConexionVpn',
        nombreVista: 'voboConexion',
        bnew:false,
        bdel:false,
        tam_pag:50,
        bedit: false,
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1],
        btestGroups: [0],
        bexcelGroups: [0,1],
        constructor: function(config) {
            Phx.vista.VoBoConexionVpn.superclass.constructor.call(this,config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'en_proceso';
            this.load({params:{start:0, limit:this.tam_pag}});

            //this.finCons = true;
            this.getBoton('ant_estado').setVisible(true);
        }

    };
</script>
