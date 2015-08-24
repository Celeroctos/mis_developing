misEngine['class']('component.expander', function(){
	return {
		config: {
			name: 'component.expander',
			id: null,
			grid: null,
			renderConfig: {
				mode: 'template',
				template: null
			}
		},
		removeExpander: function($el){
			//$el.fadeOut(300, function(){
				$el.remove();
			//});
		},
		bindHandlers: function(){
			console.debug('expander bindHandlers', this.config);
			console.debug('click', '#' + this.config.grid.getId() + ' tbody tr');
			if (!this.config.grid) {
				misEngine.t('Not exists grid for module Expander');
				return false;
			}
			
			var config = this.config;
			var self = this;
			$(document).on('click', '#' + config.grid.getId() + ' tbody tr', function(e){
				var el = e.currentTarget;
				var $el = $(el);
				
				console.debug('expander on click', $el, $el.hasClass('expander'));
				if ($el.hasClass('expander')) {//click on expander
					return false;
				}
				var $nextTr = $el.next();
				if ($nextTr.hasClass('expander')) {
					self.removeExpander($nextTr);
					return;
				}
				
				var previos = $('.expander');
				self.removeExpander(previos);
				$(this).after($('<tr>').append($('<td>').prop({
					'colspan': $('#' + config.grid.getId() + ' tbody tr:first').find('td').length
				}).html($(config.renderConfig.template).html()).append($('<div>').prop({
					'class': 'expander-arrow'
				}).css({
					'borderTopColor': $(this).find('td:first').css('backgroundColor')
				}))).prop({
					'class': 'expander'
				}));
				
			});
		},
		
		init: function(config){
			if (config) {
				this.setConfig(config);
			}
			this.bindHandlers();
			return this;
		}
	};
});
