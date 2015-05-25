misEngine.class('component.ajaxloader',function() {
	return {
		config : {
			name : 'component.ajaxloader',
            width : 16,
            height: 16
		},
		
		bindHandlers : function() {
		
		},

        generate : function() {
            return $('<img>').prop({
                'src' : '/images/ajax-loader.gif',
                'width' : this.width,
                'height' : this.height,
                'alt' : 'Загрузка...'
            });
        },
	
		init : function(config) {
            if(this.config) {
                this.setConfig(config);
            }
			return this;
		}
	};
});