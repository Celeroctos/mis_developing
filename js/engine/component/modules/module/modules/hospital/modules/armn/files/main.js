misEngine.class('component.module.hospital.armn', function() {
    return {
        activeTab : null,
        config: {
            name: 'armn'
        },

        bindHandlers : function() {
            this.changeTabHandler();
        },

        changeTabHandler : function() {
            $('#').on('shown.bs.tab', $.proxy(function(e) {
                this.activeTab = $(e.currentTarget).prop('id');
                this.reloadTab();
            }, this));
        },

        initWidgets : function() {

        },

        run : function() {
            this.bindHandlers();
            this.initWidgets();
        },

        init : function() {
            return this;
        }
    }
});