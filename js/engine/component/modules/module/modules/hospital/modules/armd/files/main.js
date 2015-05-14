misEngine.class('component.module.hospital.armd', function() {
    return {
        activeTab : null,
        config: {
            name: 'armd'
        },
        patientsGrid : null,
        myPatientsGrid : null,

        displayGrids : function() {
            this.displayPatientsGrid();
            this.displayMyPatientsGrid();
        },

        displayPatientsGrid : function() {
            this.patientsGrid = misEngine.create('component.grid');
            var patientsGridRequestData = {
                returnAsJson : true,
                id : 'patientsGrid',
                serverModel : 'PatientsGrid',
                container : '#armd-patients'
            };

            this.patientsGrid
                .setConfig({
                    id : 'patientsGrid',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/grid',
                            data : patientsGridRequestData,
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    $(patientsGridRequestData.container)
                                        .css({
                                            'textAlign' : 'left'
                                        })
                                        .html(data.data);
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                })
                .render()
                .on();
        },

        displayMyPatientsGrid : function() {
            this.myPatientsGrid = misEngine.create('component.grid');
            var myPatientsGridRequestData = {
                returnAsJson : true,
                id : 'myPatientsGrid',
                serverModel : 'PatientsGrid',
                container : '#armd-my-patients'
            };

            this.myPatientsGrid
                .setConfig({
                    id : 'myPatientsGrid',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/grid',
                            data : myPatientsGridRequestData,
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    $(myPatientsGridRequestData.container)
                                        .css({
                                            'textAlign' : 'left'
                                        })
                                        .html(data.data);
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                })
                .render()
                .on();
        },

        displayTabmarks : function() {
            this.tabmarks = [
                misEngine.create('component.tabmark', {
                    selector : '#armdMyPatientsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'QueueGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#armdMyPatientsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline')
                                    } else {
                                        $('#armdMyPatientsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#armdPatientsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'ComissionGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#armdPatientsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#armdPatientsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#armdOperationsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'HospitalizationGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#armdOperationsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#armdOperationsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                })
            ];

            $(this.tabmarks).each(function(index, element) {
                element.updateTabmark();
            });
        },

        bindHandlers : function() {
            this.changeTabHandler();

            $(document).on('click', '#patientGrids td, #myPatientsGrid td', $.proxy(function(e) {
                var window = misEngine.create('component.window', {
                    selector : $(e.target),
                    title : 'Окно пациента',
                    container : $(e.target),
                    width: '1024px',
                    content : $('.contentForPatientGrid').html()
                }).show();
            }, this));

            $(document).on('click', '.desktopTab li.last', $.proxy(function(e) {
                this.makeTemplateTab($(e.target).parent());
            }, this));


            $(document).on('click', '.desktopTab .closeTab', function(e) {

            });
        },

        makeTemplateTab : function(clickedLi) {
            var closeCross = $('<span>').prop({
                'class' : 'glyphicon glyphicon-remove closeTab'
            });

            var generator = misEngine.create('component.utils.id_generator');
            console.log(generator);
            var id = generator.getRandom();

            var li = $('<li>').prop({
                'role' : 'navigation'
            }).append(
                $('<a>').prop({
                    'data-toggle' : 'tab',
                    'role' : 'tab',
                    'aria-controls' : 't' + id,
                    'href' : '#t' + id
                }).text('Шаблон 3'), // TODO: make normal text. Here must be ajax query
                closeCross
            );

            $(clickedLi).before(li);

            var panel = $('<div>').attr({
                'id' : 't' + id,
                'class' : 'tab-pane overlay',
                'role' : 'tabpanel'
            });

            $('#armd-show-patient .tab-content').append(panel);

            $(closeCross).on('click', function(e) {
                $(li).fadeOut(100, function() {
                    $(li).prev().prop({
                        'class' : 'active'
                    });
                    $(panel).prev().prop({
                        'class' : 'active'
                    });
                    $(this).remove();
                    $(panel).remove();
                });
            });
        },

        changeTabHandler : function() {
            $('#armdMyPatientsTabmark, #armdPatientsTab, #armdOperations').on('shown.bs.tab', $.proxy(function(e) {
                this.activeTab = $(e.currentTarget).prop('id');
                this.reloadTab();
            }, this));
        },

        makeDumpData : function() {
            $('.desktopTab .last a').trigger('click');
        },

        initWidgets : function() {

        },

        run : function() {
            this.displayGrids();
            this.bindHandlers();
            this.initWidgets();
            this.makeDumpData();

            this.activeTab = 'armdMyPatientsTab'; // First opened tab in window
        },

        init : function() {
            return this;
        }
    }
});