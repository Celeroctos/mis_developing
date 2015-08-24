misEngine.class('component.module.hospital.armd', function() {
    return {
        activeTab : null,
        config: {
            name: 'armd'
        },
        patientsGrid : null,
        myPatientsGrid : null,
        designationsGrid : null,

        displayGrids : function() {
			console.debug('displayGrids');
            this.displayPatientsGrid();
            this.displayMyPatientsGrid();
        },

        displayPatientsGrid : function() {
			console.debug('displayPatientsGrid');
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


        displayDesignationsGrid : function() {
            this.designationsGrid = misEngine.create('component.grid');
            var designationsGridRequestData = {
                returnAsJson : true,
                id : 'designationsGrid',
                serverModel : 'DesignationsGrid',
                container : '.popover-window #armd-patient-purpose .gridContainer'
            };

            this.patientsGrid
                .setConfig({
                    id : 'designationsGrid',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/grid',
                            data : designationsGridRequestData,
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {

                                    $(designationsGridRequestData.container).html(data.data);
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
			console.debug('displayMyPatientsGrid')
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
			
			console.debug('displayTabmarks')
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
                this.displayDiagnosisFields();
                this.displayDesignationsGrid();
            }, this));

            $(document).on('click', '.desktopTab li.last', $.proxy(function(e) {
                // Here i do the call of templates list..
                var target = e.target;
                var popover = $(e.target).parent().popover({
                    animation: true,
                    html: true,
                    placement: 'right',
                    title: 'Выбор шаблона',
                    delay: {
                        show: 300,
                        hide: 300
                    },
                    template : $('<div>').prop({
                        'class' : 'popover popover-templates-list',
                        'role' : 'tooltip'
                    }).append(
                        $('<div>').addClass('arrow'),
                        $('<h5>').addClass('popover-title'),
                        $('<div>').addClass('popover-content')
                    ),
                    container: $(e.target),
                    content: $.proxy(function () {
                        return $('<div>').append(
                            this.getTemplatesList(e.target),
                            $('<button>')
                                .prop({
                                    'class' : 'btn btn-success'
                                })
                                .text('OK')
                                .on('click', $.proxy(function(e) {
                                     popover.popover('destroy');
                                    // TODO
                                }, this))
                        );
                    }, this)
                });

                $(e.target).parent().on('click', function(e) {
                   return false;
                });

                $(document).on('hidden.bs.popover', '.popover-templates-list', $.proxy(function(e) {
                    $('.template-choose:checked').each($.proxy(function(index, element) {
                        this.makeTemplateTab($(target).parents('li'));
                    }, this));
                }, this));

                $(e.target).parent().popover('show')

            }, this));


            $(document).on('click', '.desktopTab .closeTab', function(e) {

            });

            $(document).on('change', '.template-choose', function(e) {
                e.stopPropagation();
                return true;
            });


            $(document).on('click', '#addDrug', function(e) {
                $('.panel2').animate({
                    'marginLeft' : 1000
                });
            });

            $(document).on('click', '#addProcedure', function(e) {
                $('.panel2').animate({
                    'marginLeft' : -1000
                });
            });
        },

        getTemplatesList : function(container) {
            var ul = $('<ul>').append(
                $('<li>').addClass('list-group-item').append(
                    $('<input>').prop({
                        'type' : 'checkbox',
                        'class' : 'template-choose'
                    }),
                    $('<a>').text('Шаблон 1').prop({
                        'href' : '#'
                    })
                ),
                $('<li>').addClass('list-group-item').append(
                    $('<input>').prop({
                        'type' : 'checkbox',
                        'class' : 'template-choose'
                    }),
                    $('<a>').text('Шаблон 2').prop({
                        'href' : '#'
                    })
                ),
                $('<li>').addClass('list-group-item').append(
                    $('<input>').prop({
                        'type' : 'checkbox',
                        'class' : 'template-choose'
                    }),
                    $('<a>').text('Шаблон 3').prop({
                        'href' : '#'
                    })
                )
            ).addClass('list-group');

            return ul;
        },

        makeTemplateTab : function(clickedLi) {
            var closeCross = $('<span>').prop({
                'class' : 'glyphicon glyphicon-remove closeTab'
            });

            var generator = misEngine.create('component.utils.id_generator');
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

        displayDiagnosisFields : function() {
            this.preClinicalDiagnosis = misEngine.create('component.livesearch', {
                'labelText' : 'Предварительный клинический диагноз',
                'placeholderText' : 'Начинайте вводить...',
                'container' : '.popover-window #armd-patient-diagnosis',
                'primary' : 'id',
                'maxChoosed' : 1,
                'bindedWindowSelector' : $('#addNewCladrSettlement'),
                'beforeWindowShow' : function(callback) {
                    $.ajax({
                        'url' : '/reception/address/getsettlementform',
                        'cache' : false,
                        'dataType' : 'json',
                        'type' : 'GET',
                        'success' : function(data, textStatus, jqXHR) {
                            if(data.success == true) {
                                callback();
                            } else {

                            }
                        }
                    });
                },
                'extraparams' : {
                    //'region' : $.fn['regionChooser'].getChoosed(),
                    //'district' : $.fn['districtChooser'].getChoosed()
                },
                'rowAddHandler' : function(ul, row) {
                    $(ul).append($('<li>').text('[' + $.fn.reduceCladrCode(row.code_cladr) + '] ' + row.name));
                },
                'url' : '/guides/cladr/settlementget?page=1&rows=10&sidx=id&sord=desc&limit=10&filters=',
                'filters' : {
                    'groupOp' : 'AND',
                    'rules': [
                        {
                            'field' : 'name',
                            'op' : 'cn',
                            'data' : ''
                        }
                    ]
                }
            });
            this.clinicalDiagnosis = misEngine.create('component.livesearch', {
                'labelText' : 'Клинический диагноз',
                'placeholderText' : 'Начинайте вводить...',
                'container' : '.popover-window #armd-patient-diagnosis',
                'primary' : 'id',
                'maxChoosed' : 1,
                'bindedWindowSelector' : $('#addNewCladrSettlement'),
                'beforeWindowShow' : function(callback) {
                    $.ajax({
                        'url' : '/reception/address/getsettlementform',
                        'cache' : false,
                        'dataType' : 'json',
                        'type' : 'GET',
                        'success' : function(data, textStatus, jqXHR) {
                            if(data.success == true) {
                                callback();
                            } else {

                            }
                        }
                    });
                },
                'extraparams' : {
                    //'region' : $.fn['regionChooser'].getChoosed(),
                    //'district' : $.fn['districtChooser'].getChoosed()
                },
                'rowAddHandler' : function(ul, row) {
                    $(ul).append($('<li>').text('[' + $.fn.reduceCladrCode(row.code_cladr) + '] ' + row.name));
                },
                'url' : '/guides/cladr/settlementget?page=1&rows=10&sidx=id&sord=desc&limit=10&filters=',
                'filters' : {
                    'groupOp' : 'AND',
                    'rules': [
                        {
                            'field' : 'name',
                            'op' : 'cn',
                            'data' : ''
                        }
                    ]
                }
            });
            this.appendDiagnosis = misEngine.create('component.livesearch', {
                'labelText' : 'Сопутствующий диагноз',
                'placeholderText' : 'Начинайте вводить...',
                'container' : '.popover-window #armd-patient-diagnosis',
                'primary' : 'id',
                'maxChoosed' : 1,
                'bindedWindowSelector' : $('#addNewCladrSettlement'),
                'beforeWindowShow' : function(callback) {
                    $.ajax({
                        'url' : '/reception/address/getsettlementform',
                        'cache' : false,
                        'dataType' : 'json',
                        'type' : 'GET',
                        'success' : function(data, textStatus, jqXHR) {
                            if(data.success == true) {
                                callback();
                            } else {

                            }
                        }
                    });
                },
                'extraparams' : {
                    //'region' : $.fn['regionChooser'].getChoosed(),
                    //'district' : $.fn['districtChooser'].getChoosed()
                },
                'rowAddHandler' : function(ul, row) {
                    $(ul).append($('<li>').text('[' + $.fn.reduceCladrCode(row.code_cladr) + '] ' + row.name));
                },
                'url' : '/guides/cladr/settlementget?page=1&rows=10&sidx=id&sord=desc&limit=10&filters=',
                'filters' : {
                    'groupOp' : 'AND',
                    'rules': [
                        {
                            'field' : 'name',
                            'op' : 'cn',
                            'data' : ''
                        }
                    ]
                }
            });
            this.complicationsDiagnosis = misEngine.create('component.livesearch', {
                'labelText' : 'Осложнения основного диагноза',
                'placeholderText' : 'Начинайте вводить...',
                'container' : '.popover-window #armd-patient-diagnosis',
                'primary' : 'id',
                'maxChoosed' : 1,
                'bindedWindowSelector' : $('#addNewCladrSettlement'),
                'beforeWindowShow' : function(callback) {
                    $.ajax({
                        'url' : '/reception/address/getsettlementform',
                        'cache' : false,
                        'dataType' : 'json',
                        'type' : 'GET',
                        'success' : function(data, textStatus, jqXHR) {
                            if(data.success == true) {
                                callback();
                            } else {

                            }
                        }
                    });
                },
                'extraparams' : {
                    //'region' : $.fn['regionChooser'].getChoosed(),
                    //'district' : $.fn['districtChooser'].getChoosed()
                },
                'rowAddHandler' : function(ul, row) {
                    $(ul).append($('<li>').text('[' + $.fn.reduceCladrCode(row.code_cladr) + '] ' + row.name));
                },
                'url' : '/guides/cladr/settlementget?page=1&rows=10&sidx=id&sord=desc&limit=10&filters=',
                'filters' : {
                    'groupOp' : 'AND',
                    'rules': [
                        {
                            'field' : 'name',
                            'op' : 'cn',
                            'data' : ''
                        }
                    ]
                }
            });
        },

        run : function() {
			console.debug('run');
            this.displayGrids();
            this.bindHandlers();
            this.initWidgets();
           // this.makeDumpData();

            this.activeTab = 'armdMyPatientsTab'; // First opened tab in window
        },

        init : function() {
            return this;
        }
    }
});