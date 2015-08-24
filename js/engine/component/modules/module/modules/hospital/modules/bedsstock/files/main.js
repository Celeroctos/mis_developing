misEngine['class']('component.module.hospital.bedsstock', function(){
	return {
		patientsGrid: null,
		relocationsGrid: null,
		activeTab: null,
		demo: true,//презентация
		config: {
			name: 'bedsstock'
		},
		
		displayGrids: function(){
			this.displayPatientsGrid();
			this.displayRelocationsGrid();
		},
		
		displayPatientsGrid: function(){
			console.debug('displayPatientsGrid')
			
			this.patientsGrid = misEngine.create('component.grid');
			var patientsGridRequestData = {
				returnAsJson: true,
				id: 'patientsGrid',
				serverModel: 'PatientsGrid',
				container: '#patients'
			};
			var demo = this.demo;
			this.patientsGrid.setConfig({
				id: 'patientsGrid',
				renderConfig: {
					mode: 'ajax',
					ajaxConf: {
						url: '/hospital/components/grid',
						data: patientsGridRequestData,
						dataType: 'json',
						success: function(data, status, jqXHR){
							if (data.success && !demo) {
								$(patientsGridRequestData.container).css({
									'textAlign': 'left'
								}).html(data.data);
							}
						},
						error: function(jqXHR, status, errorThrown){
							misEngine.t(jqXHR, status, errorThrown);
						}
					}
				}
			}).render().on();
			
			var expanderBody = $('.bedsstockExpanderBody');
			expanderBody.find('li.new').remove();
			expanderBody.find('.wardsList li').addClass('default-margin-bottom');
			
			var expander = misEngine.create('component.expander', {
				grid: this.patientsGrid,
				renderConfig: {
					template: expanderBody
				}
			});
		},
		
		displayRelocationsGrid: function(){
			console.debug('displayRelocationsGrid')
			this.relocationsGrid = misEngine.create('component.grid');
			var relocationsGridRequestData = {
				returnAsJson: true,
				id: 'relocationsGrid',
				serverModel: 'PatientsGrid',
				container: '#relocations'
			};
			
			this.relocationsGrid.setConfig({
				id: 'relocationsGrid',
				renderConfig: {
					mode: 'ajax',
					ajaxConf: {
						url: '/hospital/components/grid',
						data: relocationsGridRequestData,
						dataType: 'json',
						success: function(data, status, jqXHR){
							if (data.success) {
								$(relocationsGridRequestData.container).css({
									'textAlign': 'left'
								}).html(data.data);
							}
						},
						error: function(jqXHR, status, errorThrown){
							misEngine.t(jqXHR, status, errorThrown);
						}
					}
				}
			}).render().on();
			
			var expander = misEngine.create('component.expander', {
				grid: this.relocationsGrid,
				renderConfig: {
					template: '.relocationExpanderBody'
				}
			});
		},
		
		bindHandlers: function(){
			$(document).on('click', '.wardsChoose .wardsList li:not(.new)', function(){
				console.debug('onclick');
				
				$('.wardsWidget').css({
					'position': 'relative'
				}).prepend($('<div>').addClass('overlay').css('top', '-5px'));
				// Here must be ajaxQuery to give all beds in ward
				$('.overlay').remove();
				
				$(this).parents('.wrap').animate({
					'marginLeft': '-100%'
				}, 500)
				
			});
			
			$(document).on('click', '.expander .back', function(){
				console.debug('onclick');
				$(this).parents('.wrap').animate({
					'marginLeft': '0%'
				}, 500)
			});
			
			$(document).on('click', '.reserveBed', function(){
				console.debug('onclick');
				$('.reserveForm').fadeOut(200);
				$(this).parents('li').find('.reserveForm').css('display', 'inline-block').show(400);
				return false;
			});
			
			$(document).on('click', '.relocationForm .acceptRelocation', function(e){
				console.debug('onclick');
				// Here must be ajax Query TODO
				$(this).parents('tr.expander').fadeOut(300, function(){
					$(e.target).parents('tr.expander').remove();
				});
			});
			
			$(document).on('click', '.reserveForm .acceptReserve', function(e){
				console.debug('onclick');
				// Here must be ajax Query TODO
				$(e.target).parents('tr.expander').fadeOut(300, function(){
					$(e.target).parents('tr.expander').remove();
				})
			});
			
			this.changeTabHandler();
		},
		
		changeTabHandler: function(){
			$('#bedsstockPatientsTab, #bedsstockPatientsTab, #bedsstockRelocationsTab, #bedsstockWardsTab').on('shown.bs.tab', $.proxy(function(e){
				this.activeTab = $(e.currentTarget).prop('id');
				this.reloadTab();
			}, this));
		},
		
		initWidgets: function(){
			var widget = misEngine.create('component.widget.wards').run();
			var webspeech = misEngine.create('component.webspeech', {
				lang: 'ru',
				continuous: true,
				interimResults: true,
				iconContainer: '#voiceIcon'
			});
		},
		
		run: function(){
			console.debug('run');
			this.displayGrids();
			this.bindHandlers();
			this.initWidgets();
			
			this.activeTab = 'bedsstockPatientsTab'; // First opened tab in window
		},
		
		init: function(){
			return this;
		}
	}
});
