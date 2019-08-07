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
    Phx.vista.RegistroConexionVpn = {
        require:'../../../sis_conexionVPN/vista/conexion_vpn/ConexionVpn.php',
        requireclase:'Phx.vista.ConexionVpn',
        title:'RegistroConexionVpn',
        nombreVista: 'solicitudConexion',
        bnew:true,
        bdel:true,
        bedit: true,
        tam_pag:50,

        gruposBarraTareas:[

            {name:'borrador',title:'<H1 align="center"><i class="fa fa-list-ul"></i>En Borrador</h1>',grupo:0,height:0},
            {name:'aprobacion',title:'<H1 align="center"><i class="fa fa-list-ul"></i>En Aprobación</h1>',grupo:1,height:0},
            {name:'configuracion',title:'<H1 align="center"><i class="fa fa-list-ul"></i>En Configuración</h1>',grupo:2,height:0},
            {name:'concluido',title:'<H1 align="center"><i class="fa fa-thumbs-o-up"></i>Concluidos</h1>',grupo:3,height:0}

        ],
        bnewGroups: [0],
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3],
        bexcelGroups: [0,1,2,3],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                if(name == 'borrador'){
                    this.getBoton('ant_estado').setVisible(false);
                }
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        
        constructor: function(config) {
            
            Phx.vista.RegistroConexionVpn.superclass.constructor.call(this,config);
            this.store.baseParams.pes_estado = 'borrador';
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            //this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ant_estado').setVisible(false);
            /*this.getBoton('btnChequeoDocumentosWf').setVisible(false);
            this.getBoton('btnObs').setVisible(false);*/

        },
        /*
        onSubmit: function(o,x, force){

            var msg = 'El campo IP del equipo remoto tiene la siguiente estructura: '+this.Cmp.ip_equipo_remoto.getValue();

            Ext.Msg.confirm('VALIDAR', msg + '<br> desea continuar con el registro. ', function (btn) {
                console.log('boton', btn);
                if (btn == 'yes') {

                    Phx.vista.RegistroConexionVpn.superclass.onSubmit.call(this,o);
                } else {
                }
            },this);
            //Phx.vista.ConexionVpn.superclass.onSubmit.call(this,o);
        },*/

        successSave: function (resp) {
            this.store.baseParams.pes_estado = 'borrador';
            Phx.vista.RegistroConexionVpn.superclass.successSave.call(this,resp);
        }

    };
</script>