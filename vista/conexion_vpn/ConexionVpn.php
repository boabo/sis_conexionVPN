<?php
/**
 *@package pXP
 *@file gen-ConexionVpn.php
 *@author  (franklin.espinoza)
 *@date 05-06-2017 19:34:44
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConexionVpn=Ext.extend(Phx.gridInterfaz,{

        bsave:false,
        btest:false,
        fwidth: '70%',
        fheight: '60%',
        nombreVista: 'ConexionVpn',
        constructor:function(config){
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.ConexionVpn.superclass.constructor.call(this,config);
            this.init();
            this.store.baseParams.tipo_interfaz=this.nombreVista;
            this.store.baseParams.pes_estado = 'borrador';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.inciarEventos();

            this.addButton('ant_estado',{
                grupo: [0],
                argument: {estado: 'anterior'},
                text: 'Anterior',
                iconCls: 'batras',
                disabled: true,
                handler: this.antEstado,
                tooltip: '<b>Volver al Anterior Estado</b>'
            });

            this.addButton('sig_estado',{
                grupo:[0],
                text:'Siguiente',
                iconCls: 'badelante',
                disabled:true,
                handler:this.sigEstado,
                tooltip: '<b>Pasar al Siguiente Estado</b>'
            });

            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                grupo: [0,1,2,3],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
            });

            this.addButton('btnObs',{
                grupo:[0,1,2,3],
                text :'Obs Wf.',
                iconCls : 'bchecklist',
                disabled: true,
                handler : this.onOpenObs,
                tooltip : '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
            });

            this.addButton('diagrama_gantt',{
                grupo:[0,1,2,3],
                text:'Gant',
                iconCls: 'bgantt',
                disabled:true,
                handler:this.diagramGantt,
                tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            });

            this.getBoton('ant_estado').setVisible(false);
        },


        Grupos: [
            {
                layout: 'column',
                border: false,
                defaults: {
                    border: false
                },

                items: [
                    {
                        bodyStyle: 'padding-right:10px;',
                        items: [

                            {
                                xtype: 'fieldset',
                                title: 'DATOS BASICOS',
                                autoHeight: true,
                                width: 450,
                                items: [/*this.compositeFields()*/],
                                id_grupo: 0
                            }

                        ]
                    }
                    ,
                    {
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'DATOS DEL SERVICIO',
                                autoHeight: true,
                                width:450,
                                items: [],
                                id_grupo: 1
                            }
                        ]
                    }
                ]
            }],

        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_conexion_vpn'
                },
                type:'Field',
                form:true
            },{
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_estado_wf'
                },
                type: 'Field',
                form: false,
                id_grupo:0
            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_proceso_wf'
                },
                type: 'Field',
                form: false,
                id_grupo:0
            },
            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'No. Tramite',
                    allowBlank: false,
                    anchor: '50%',
                    gwidth: 120,
                    maxLength:100,
                    renderer: function(value, p, record) {
                        return String.format('<div ext:qtip="Nro. Tramite"><b>{0}</b><br></div>', value);

                    }
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.nro_tramite',type:'string'},
                grid:true,
                form:false,
                bottom_filter : true
            },
            {
                config: {
                    name: 'estado',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength: 100,
                    renderer: function(value, p, record) {
                        return String.format('<div ext:qtip="Estado" style="color: green;"><b>{0}</b><br></div>', value);

                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'cvpn.estado', type: 'string'},
                /*id_grupo: 1,*/
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'tipo_empleado',
                    fieldLabel: 'Tipo Empleado',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25,
                    items:[
                        {boxLabel:'Personal BoA', name: 'tip_fun', inputValue:'personal_boa',qtip:'Personal de Planta', checked: 'check'},
                        {boxLabel:'Personal Externo', name: 'tip_fun', inputValue:'personal_externo',qtip:'Personal Eventual, Consultores.'}

                    ]
                },
                type: 'RadioGroupField',
                filters:{pfiltro:'cvpn.tipo_empleado',type:'string'},
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config:{
                    name: 'nombre_funcionario',
                    fieldLabel: 'Nombre Personal Externo',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:70/*,
                     hidden: true*/
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.nombre_funcionario',type:'string'},
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter : true
            },
            /*{
             config: {
             name: 'id_uo_gerencia',
             fieldLabel: 'Gerencia',
             allowBlank: true,
             emptyText: 'Elija una opción...',
             store: new Ext.data.JsonStore({
             url: '../../sis_/control/Clase/Metodo',
             id: 'id_',
             root: 'datos',
             sortInfo: {
             field: 'nombre',
             direction: 'ASC'
             },
             totalProperty: 'total',
             fields: ['id_', 'nombre', 'codigo'],
             remoteSort: true,
             baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
             }),
             valueField: 'id_',
             displayField: 'nombre',
             gdisplayField: 'desc_',
             hiddenName: 'id_uo_gerencia',
             forceSelection: true,
             typeAhead: false,
             triggerAction: 'all',
             lazyRender: true,
             mode: 'remote',
             pageSize: 15,
             queryDelay: 1000,
             anchor: '100%',
             gwidth: 150,
             minChars: 2,
             renderer : function(value, p, record) {
             return String.format('{0}', record.data['desc_']);
             }
             },
             type: 'ComboBox',
             id_grupo: 0,
             filters: {pfiltro: 'movtip.nombre',type: 'string'},
             grid: true,
             form: true
             },*/

            {
                config: {
                    name: 'id_funcionario',
                    fieldLabel: 'Funcionario Solicitante',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    qtip:'Funcionario que solicita conexion VPN.',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1'}//#FUNCAR.nombre_cargo
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    gdisplayField: 'desc_funcionario1',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    hiddenName: 'id_funcionario',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    anchor: '100%',
                    width: 260,
                    gwidth: 200,
                    minChars: 2,
                    resizable:true,
                    listWidth:'321',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario1']);
                    }
                },
                type: 'ComboBox',
                bottom_filter:true,
                id_grupo: 0,
                filters:{
                    pfiltro:'vfc.desc_funcionario1',//#fun.nombre_cargo
                    type:'string'
                },
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'id_funcionario_contacto',
                    fieldLabel: 'Funcionario de Contacto',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    qtip:'Funcionario que solicita conexion VPN.',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1'}//#FUNCAR.nombre_cargo
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    gdisplayField: 'desc_funcionario1',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    hiddenName: 'id_funcionario_contacto',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    anchor: '100%',
                    width: 260,
                    gwidth: 200,
                    minChars: 2,
                    resizable:true,
                    listWidth:'321',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['fun_contacto']);
                    }
                },
                type: 'ComboBox',
                bottom_filter:true,
                id_grupo: 0,
                filters:{
                    pfiltro:'vf.desc_funcionario1',//#fun.nombre_cargo
                    type:'string'
                },
                grid: true,
                form: true
            },

            {
                config:{
                    name: 'id_uo_gerencia',
                    fieldLabel: 'Gerencia',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 280,
                    maxLength:70,
                    disabled:true
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.id_uo_gerencia',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config: {
                    name: 'tipo_servicio',
                    fieldLabel: 'Tipo Servicio',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 120,
                    maxLength: 25,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:[ 'Conexión VPN Client', 'Conexión WIFI'],
                    style:'text-transform:uppercase;'
                },
                type: 'ComboBox',
                filters: {pfiltro: 'cvpn.tipo_servicio', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },

            {
                config:{
                    name: 'fecha_desde',
                    fieldLabel: 'Habilitar Desde',
                    allowBlank: false,
                    anchor: '70%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cvpn.fecha_desde',type:'date'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'fecha_hasta',
                    fieldLabel: 'Habilitar Hasta',
                    allowBlank: false,
                    anchor: '70%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cvpn.fecha_hasta',type:'date'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'ip_equipo_remoto',
                    fieldLabel: 'IP del equipo Remoto',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 120,
                    maxLength:15,
                    //regex: new RegExp('/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/'),
                    //regexText: 'Por favor ingrese una ip valida.'
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.ip_equipo_remoto',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },


            {
                config:{
                    name: 'lista_servicios',
                    fieldLabel: 'Lista de Servicios a Utilizar',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:1000
                },
                type:'TextArea',
                filters:{pfiltro:'cvpn.lista_servicios',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },

            {
                config:{
                    name: 'nota_adicional',
                    fieldLabel: 'Notas Adicionales',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:1000
                },
                type:'TextArea',
                filters:{pfiltro:'cvpn.nota_adicional',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },

            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cvpn.fecha_reg',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:300
                },
                type:'TextField',
                filters:{pfiltro:'cvpn.usuario_ai',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'id_usuario_ai',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'cvpn.id_usuario_ai',type:'numeric'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cvpn.fecha_mod',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            }
        ],
        tam_pag:50,
        title:'Conexion VPN',
        ActSave:'../../sis_conexionVPN/control/ConexionVpn/insertarConexionVpn',
        ActDel:'../../sis_conexionVPN/control/ConexionVpn/eliminarConexionVpn',
        ActList:'../../sis_conexionVPN/control/ConexionVpn/listarConexionVpn',
        id_store:'id_conexion_vpn',
        fields: [
            {name:'id_proceso_wf', type: 'numeric'},
            {name:'id_estado_wf', type: 'numeric'},
            {name:'estado', type: 'string'},
            {name:'nro_tramite', type: 'string'},
            {name:'id_conexion_vpn', type: 'numeric'},
            {name:'fecha_desde', type: 'date',dateFormat:'Y-m-d'},
            {name:'tipo_empleado', type: 'string'},
            {name:'ip_equipo_remoto', type: 'string'},
            {name:'fecha_hasta', type: 'date',dateFormat:'Y-m-d'},
            {name:'estado_reg', type: 'string'},
            {name:'tipo_servicio', type: 'string'},
            {name:'lista_servicios', type: 'string'},
            {name:'id_funcionario', type: 'numeric'},
            {name:'id_funcionario_contacto', type: 'numeric'},
            {name:'nota_adicional', type: 'string'},
            {name:'nombre_funcionario', type: 'string'},
            {name:'id_uo_gerencia', type: 'numeric'},
            {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'usuario_ai', type: 'string'},
            {name:'id_usuario_reg', type: 'numeric'},
            {name:'id_usuario_ai', type: 'numeric'},
            {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'id_usuario_mod', type: 'numeric'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'},
            'desc_funcionario1',
            'fun_contacto'

        ],
        sortInfo:{
            field: 'id_conexion_vpn',
            direction: 'ASC'
        },

        diagramGantt: function(){
            var data=this.sm.getSelected().data.id_proceso_wf;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                params:{'id_proceso_wf':data},
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },

        loadCheckDocumentosRecWf:function() {
            var rec=this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },

        onOpenObs:function() {
            var rec=this.sm.getSelected();
            var data = {
                id_proceso_wf: rec.data.id_proceso_wf,
                id_estado_wf: rec.data.id_estado_wf,
                num_tramite: rec.data.nro_tramite
            }
            Phx.CP.loadWindows('../../../sis_workflow/vista/obs/Obs.php',
                'Observaciones del WF',
                {
                    width:'80%',
                    height:'70%'
                },
                data,
                this.idContenedor,
                'Obs'
            )
        },

        sigEstado: function() {
            var rec = this.sm.getSelected();

            this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                'Estado de Wf',
                {
                    modal: true,
                    width: 700,
                    height: 450
                },
                {
                    data: {
                        id_estado_wf: rec.data.id_estado_wf,
                        id_proceso_wf: rec.data.id_proceso_wf
                    }
                }, this.idContenedor, 'FormEstadoWf',
                {
                    config: [{
                        event: 'beforesave',
                        delegate: this.onSaveWizard,
                    }],
                    scope: this
                }
            );
        },

        onSaveWizard:function(wizard,resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_conexionVPN/control/ConexionVpn/siguienteEstadoConexion',
                params:{
                    id_proceso_wf_act:  resp.id_proceso_wf_act,
                    id_estado_wf_act:   resp.id_estado_wf_act,
                    id_tipo_estado:     resp.id_tipo_estado,
                    id_funcionario_wf:  resp.id_funcionario_wf,
                    id_depto_wf:        resp.id_depto_wf,
                    obs:                resp.obs,
                    json_procesos:      Ext.util.JSON.encode(resp.procesos)
                },
                success:function (resp) {
                    Phx.CP.loadingHide();
                    resp.argument.wizard.panel.destroy();
                    this.reload();
                },
                failure: this.conexionFailure,
                argument:{wizard:wizard},
                timeout:this.timeout,
                scope:this
            });
        },

        antEstado:function(res){
            var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal:true,
                    width:450,
                    height:250
                }, { data:rec.data, estado_destino: res.argument.estado }, this.idContenedor,'AntFormEstadoWf',
                {
                    config:[{
                        event:'beforesave',
                        delegate: this.onAntEstado,
                    }
                    ],
                    scope:this
                })
        },

        onAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_conexionVPN/control/ConexionVpn/anteriorEstadoConexion',
                params:{
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf:  resp.id_estado_wf,
                    obs: resp.obs,
                    estado_destino: resp.estado_destino
                },
                argument:{wizard:wizard},
                success:function (resp) {
                    Phx.CP.loadingHide();
                    resp.argument.wizard.panel.destroy();
                    this.reload();
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        
        inciarEventos: function(){
            this.ocultarComponente(this.Cmp.nombre_funcionario);
            this.ocultarComponente(this.Cmp.id_funcionario_contacto);


            this.Cmp.tipo_empleado.on('change',function (cb, value) {

                if(value.inputValue == 'personal_boa'){
                    Ext.Ajax.request({
                        url:'../../sis_conexionVPN/control/ConexionVpn/cargarDatos',
                        params: {usuario:0},
                        success:function(resp){
                            var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                            this.Cmp.id_funcionario.setValue(reg.ROOT.datos.v_id_funcionario);
                            this.Cmp.id_funcionario.setRawValue(reg.ROOT.datos.v_desc_funcionario);
                        },
                        failure: this.conexionFailure,
                        timeout:this.timeout,
                        scope:this
                    });
                    this.ocultarComponente(this.Cmp.nombre_funcionario);
                    this.ocultarComponente(this.Cmp.id_funcionario_contacto);
                    this.mostrarComponente(this.Cmp.id_funcionario);
                    this.Cmp.id_funcionario_contacto.reset();
                    //this.Cmp.nombre_funcionario.reset();

                }else{
                    Ext.Ajax.request({
                        url:'../../sis_conexionVPN/control/ConexionVpn/cargarDatos',
                        params: {usuario:0},
                        success:function(resp){
                            var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                            this.Cmp.id_funcionario_contacto.setValue(reg.ROOT.datos.v_id_funcionario);
                            this.Cmp.id_funcionario_contacto.setRawValue(reg.ROOT.datos.v_desc_funcionario);
                        },
                        failure: this.conexionFailure,
                        timeout:this.timeout,
                        scope:this
                    });
                    this.ocultarComponente(this.Cmp.id_funcionario);
                    this.mostrarComponente(this.Cmp.nombre_funcionario);
                    this.mostrarComponente(this.Cmp.id_funcionario_contacto);
                    this.Cmp.id_funcionario.reset();
                }
            },this);

            this.Cmp.fecha_desde.on('change',function( o, newValue, oldValue ){
                this.Cmp.fecha_hasta.setMinValue(newValue);
                this.Cmp.fecha_hasta.reset();

            }, this);

            /*this.Cmp.id_funcionario.on('select',function (cmp, rec) {
             Ext.Ajax.request({
             url:'../../sis_conexionVPN/control/ConexionVpn/cargarGerencia',
             params:{id_funcionario:this.Cmp.id_funcionario.getValue()},
             success:function(resp){
             var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
             console.log(reg);
             this.Cmp.id_uo_gerencia.setValue(reg.ROOT.datos.v_gerencia);
             },
             failure: this.conexionFailure,
             timeout:this.timeout,
             scope:this
             });
             },this);*/
        },

        preparaMenu: function(n)
        {	var rec = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ConexionVpn.superclass.preparaMenu.call(this,n);
            this.getBoton('ant_estado').enable();
            this.getBoton('sig_estado').enable();
            this.getBoton('btnChequeoDocumentosWf').enable();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
            //this.getBoton('edit').enable();
            //this.getBoton('del').enable();
            //this.getBoton('reportes').enable();


            //return tb;
        },
        
        liberaMenu:function(){
            var tb = Phx.vista.ConexionVpn.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
                this.getBoton('diagrama_gantt').setDisabled(true);
                this.getBoton('btnObs').disable();
                //this.getBoton('edit').disable();
                //this.getBoton('del').disable();
            }
            return tb
        },

        onButtonNew : function () {
            Ext.Ajax.request({
                url:'../../sis_conexionVPN/control/ConexionVpn/cargarDatos',
                params: {usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    console.log(reg);
                    this.Cmp.id_funcionario.setValue(reg.ROOT.datos.v_id_funcionario);
                    this.Cmp.id_funcionario.setRawValue(reg.ROOT.datos.v_desc_funcionario);
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            Phx.vista.ConexionVpn.superclass.onButtonNew.call(this);


        },

        onButtonEdit : function () {
            Phx.vista.ConexionVpn.superclass.onButtonEdit.call(this);
            var rec = this.getSelectedData();
            console.log(rec);

            if(rec.nombre_funcionario != '') {
                console.log('entra');
                this.Cmp.id_funcionario_contacto.setValue(rec.id_funcionario_contacto);
                this.Cmp.id_funcionario_contacto.setRawValue(rec.fun_contacto);
            }


        }

    })
</script>

