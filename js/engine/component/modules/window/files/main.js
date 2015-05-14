misEngine.class('component.window', function() {
	return {
		config : {
			name : 'component.window',
            selector : null, // This is binding to selector
            width : '500px',
            height : 'auto',
            title : ''
        },
        window : null,
		
		bindHandlers : function() {
            $(this).on('show', $.proxy(function(e) {
                this.show();
            }, this));
            $(this).on('hide', $.proxy(function(e) {
                this.hide();
            }, this));

            $(document).on('click', '.popover-window', function(e) {
                e.stopPropagation();
                return false;
            });
		},

        show : function() {
            var window = this.createWindow();
        },

        createWindow : function() {
            // TODO : place popover here
            this.window = $(this.config.selector).popover({
                animation: true,
                html: true,
                placement: 'bottom',
                title: this.config.title,
                delay: {
                    show: 300,
                    hide: 300
                },
                trigger : 'manual',
                template : $('<div>').prop({
                    'class' : 'popover-window',
                    'role' : 'tooltip'
                }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content'), $('<div>').addClass('popover-footer')).css({
                    'width' : this.config.width,
                    'height' : this.config.height
                }),
                container: $(this.config.container),
                content: $.proxy(function () {
                    return this.config.content;
                }, this)
            });

            $(this.config.selector).popover('show');

            var span = $('<span class="glyphicon glyphicon-remove" title="Закрыть окно">').css({
                position: 'absolute',
                cursor: 'pointer',
                left: this.config.crossLeft
            });

            $(span).on('click', function(e) {
                $(this.config.selector).popover('destroy');
                $(span).trigger('close');
                e.stopPropagation();
                return false;
            });

            $(this.config.selector).parent().find('.popover').append(span).on('click', function() {
                return false;
            });

            return this.window;
        },

        destroyWindow : function(e) {
            $(this.window).remove();
        },

        hide : function() {

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