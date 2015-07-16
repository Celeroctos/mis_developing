misEngine.class('component.utils', function() {
    return {
        config: {
            name: 'component.utils',
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