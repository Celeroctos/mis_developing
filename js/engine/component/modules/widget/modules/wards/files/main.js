misEngine.class('component.widget.wards', function() {
    return {
        config: {
            name: 'component.widget.wards',
            id: null
        },
        popover : null,

        bindHandlers : function() {
            $(document).on('click', '.wardsList .settings', $.proxy(function(e) {
                if(!this.popover) {
                    this.initPopover();
                } else {
                    this.hidePopover();
                }
                this.initPopover($(e.target));
            }, this));

            $(document).on('click', '#addNewWard', function(e) {
               $(this).prop({
                   'disabled' : true
               });
               var popover =  $(this).popover({
                   animation: true,
                   html: true,
                   placement: 'auto',
                   title: 'Добавить палату',
                   delay: {
                       show: 300,
                       hide: 300
                   },
                   template : $('<div>').prop({
                       'class' : 'popover popover-wardedit',
                       'role' : 'tooltip'
                   }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                   container: $(li),
                   content: $.proxy(function () {
                       return $('.settingsFormCont').html();
                   }, this)
               });
                $(this).popover('show');
                $(this).find('.popover').on('click', function() {
                    return false;
                })
            });
        },

        initPopover : function(li) {
            this.popover = $(li).popover({
                animation: true,
                html: true,
                placement: 'bottom',
                title: 'Настройки',
                delay: {
                    show: 300,
                    hide: 300
                },
                template : $('<div>').prop({
                    'class' : 'popover popover-wardedit',
                    'role' : 'tooltip'
                }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                container: $(li),
                content: $.proxy(function () {
                    return $('.settingsFormCont').html();
                }, this)
            });
            $(li).popover('show');
            $(li).find('.popover').on('click', function() {
                return false;
            })
        },

        hidePopover : function() {
            this.popover.popover('destroy');
        },

        run : function() {
            this.bindHandlers();
            return this;
        },

        init : function(config) {
            if(this.config) {
                this.setConfig(config);
            }
            return this;
        }
    }
});