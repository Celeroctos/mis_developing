misEngine.class('component.utils.id_generator', function() {
    return {
        config: {
            name: 'component.utils.id_generator',
            id: null
        },

        getRandom : function() {
            var date = new Date();
            return date.getMinutes() + '' + date.getSeconds() + '' + date.getMilliseconds();
        },

        init : function(config) {
            if(this.config) {
                this.setConfig(config);
            }
            return this;
        }
    }
});