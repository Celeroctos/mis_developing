misEngine.class('component.widget', function() {
    return {
        config: {
            name: 'component.widget',
            id: null
        },

        init : function(config) {
            if(this.config) {
                this.setConfig(config);
            }
            return this;
        }
    }
});