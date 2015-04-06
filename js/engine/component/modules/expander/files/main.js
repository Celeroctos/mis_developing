misEngine.class('component.expander', function() {
    return {
        config : {
            name : 'component.expander',
            id : null,
            grid : null,
            renderConfig : {
                mode : 'template',
                template : null
            }
        },

        bindHandlers : function() {
            if(!this.config.grid) {
                misEngine.t('Not exists grid for module Expander');
                return false;
            }

            var config = this.config;

            $(document).on('click', '#' + config.grid.getId() + ' tbody tr', function(e) {
                if($(this).hasClass('expander')) {
                    return false;
                }

                var previos = $('.expander');

                $(this).after(
                    $('<tr>').append(
                        $('<td>').prop({
                            'colspan' : $('#' + config.grid.id + ' tr:first').find('td').length
                        }).html(
                            $(config.renderConfig.template).html()
                        ).append(
                            $('<div>').prop({
                                'class' : 'expander-arrow'
                            }).css({
                                'borderTopColor' : $(this).find('td:first').css('backgroundColor')
                            })
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

        init : function(config) {
            if(config) {
                this.setConfig(config);
            }
            this.bindHandlers();
            return this;
        }
    };
});