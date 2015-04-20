misEngine.class('component.module.hospital.bedsstock', function() {
    return {
        patientsGrid : null,
        relocationsGrid : null,
        activeTab : null,
        config: {
            name: 'bedsstock'
        },

        displayGrids : function() {
            this.displayPatientsGrid();
            this.displayRelocationsGrid();
        },

        displayPatientsGrid : function() {
            this.patientsGrid = misEngine.create('component.grid');
            var patientsGridRequestData = {
                returnAsJson : true,
                id : 'patientsGrid',
                serverModel : 'PatientsGrid',
                container : '#patients'
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

            var expanderBody = $('.bedsstockExpanderBody');
            expanderBody.find('li.new').remove();
            expanderBody.find('.wardsList li').addClass('default-margin-bottom');

            var expander = misEngine.create('component.expander', {
                grid : this.patientsGrid,
                renderConfig : {
                    template : expanderBody
                }
            });
        },

        displayRelocationsGrid : function() {
            this.relocationsGrid = misEngine.create('component.grid');
            var relocationsGridRequestData = {
                returnAsJson : true,
                id : 'relocationsGrid',
                serverModel : 'PatientsGrid',
                container : '#relocations'
            };

            this.relocationsGrid
                .setConfig({
                    id : 'relocationsGrid',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/grid',
                            data : relocationsGridRequestData,
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    $(relocationsGridRequestData.container)
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

            var expander = misEngine.create('component.expander', {
                grid : this.relocationsGrid,
                renderConfig : {
                    template : '.relocationExpanderBody'
                }
            });
        },

        bindHandlers : function() {
            $(document).on('click', '.wardsChoose .wardsList li:not(.new)', function() {
                $(this).parents('.wrap').animate({
                    'marginLeft' : '-100%'
                }, 500)
            });

            $(document).on('click', '.expander .back', function() {
                $(this).parents('.wrap').animate({
                    'marginLeft' : '0%'
                }, 500)
            });

            $(document).on('click', '.reserveBed', function(){
                $('.reserveForm').fadeOut(200);
                $(this).parents('li').find('.reserveForm').css('display', 'inline-block').show(400);
                return false;
            });

            this.changeTabHandler();
        },

        changeTabHandler : function() {
            $('#bedsstockPatientsTab, #bedsstockPatientsTab, #bedsstockRelocationsTab, #bedsstockWardsTab').on('shown.bs.tab', $.proxy(function(e) {
                this.activeTab = $(e.currentTarget).prop('id');
                this.reloadTab();
            }, this));
        },

        initWidgets : function() {
            var widget = misEngine.create('component.widget.wards').run();
        },

        run : function() {
            this.displayGrids();
            this.bindHandlers();
            this.initWidgets();

            this.activeTab = 'bedsstockPatientsTab'; // First opened tab in window
        },

        init : function() {
            return this;
        }
    }
});