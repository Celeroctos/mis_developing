misEngine.class('component.module.hospital.bedsstock', function() {
    return {
        patientsGrid : null,
        config: {
            name: 'bedsstock'
        },

        displayGrids : function() {
            this.displayPatientsGrid();
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

            this.addRowExpander(this.patientsGrid);
        },

        addRowExpander : function(grid) {
            $(document).on('click', '.bedssotckTablesCont tbody tr', function(e) {
                if($(this).hasClass('expander')) {
                    return false;
                }
                var previos = $('.expander');

                $(this).after(
                   $('<tr>').append(
                      $('<td>').prop({
                         'colspan' : $(this).find('td').length,
                      }).html(
                          $('.bedsstockExpanderBody').html()
                      )
                   ).prop({
                      'class' : 'expander'
                   })
                );

                previos.fadeOut(300, function() {
                    previos.remove();
                });
            });
        },

        bindHandlers : function() {
            $(document).on('click', '.wardsList li', function() {
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
        },

        run : function() {
            this.displayGrids();
            this.bindHandlers();
        },

        init : function() {
            return this;
        }
    }
});