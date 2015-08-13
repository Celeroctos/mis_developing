$(document).ready(function(e){
	$('#toHospitalizationBtn').on('click', function(e){
		$('.directionsList').addClass('no-display');
		$('.directionAdd').removeClass('no-display');
	});
	
	$('#directionAddClose').on('click', function(e, withRefresh){
		$('.directionsList').removeClass('no-display');
		$('.directionAdd').addClass('no-display');
		if (withRefresh) {
			$('.directionsList').trigger('refresh');
		}
	});
	
	$('.directionsList').on('refresh', function(e){
		var overlay = $('<div>').addClass('overlay');
		$(this).css({
			'position': 'static'
		}).prepend(overlay);
		
		$.ajax({
			'url': '/hospital/mdirections/get',
			'data': {
				'omsId': $('#directionOmsId').val(),
				'cardNumber': $('#cardNumber').val()
			},
			'cache': false,
			'dataType': 'json',
			'type': 'GET',
			'success': $.proxy(function(data, textStatus, jqXHR){
				if (data.success) {
					overlay.remove();
					$('.directionsList .cont a').remove();
					for (var i = 0; i < data.directions.length; i++) {
						var item=data.directions[i];
						console.debug(item);
						
						var writeType=item.write_type;
						switch(writeType){
							case 1:
								var destFIO=[(item.last_name?item.last_name:'-'),(item.first_name?item.first_name:'-'),(item.middle_name?item.middle_name:'-')].join(' ');
								
								var text='На консультацию в ' + data.directions[i].shortname + ' к '+item.post_name+' '+destFIO+ ' от ' + data.directions[i].create_date 
								+ (item.date_dest?' на '+item.date_dest:'');
							break;
							default:
								var text='На госпитализацию в ' + data.directions[i].shortname + ' от ' + data.directions[i].create_date;
							break;
						}
						$('.directionsList .cont').prepend($('<li>').append($('<span>').prop({
							//'href': 'dir' + data.directions[i].id
						}).text(text)));
					}
				}
			}, this)
		});
	});
	
	$('#directionAddSubmit').on('click', function(e){
		var overlay = $('<div>').addClass('overlay');
		$(this).parents('.overlayCont').css({
			'position': 'static'
		}).prepend(overlay);
		
		$.ajax({
			'url': '/hospital/mdirections/add',
			'data': $(this).parents('form').serialize(),
			'cache': false,
			'dataType': 'json',
			'type': 'POST',
			'success': $.proxy(function(data, textStatus, jqXHR){
				if (data.success) {
					overlay.remove();
					$('#directionAddClose').trigger('click', [1]);
				}
			}, this)
		});
	});
	
	// First refresh after page loading
	$('.directionsList').trigger('refresh');
	
	
	$form = $('#add-direction-to-consultation-form');
	$enterprises=$('#FormDirectionForPatientAdd_enterpriseId', $form);
	$wards=$('#FormDirectionForPatientAdd_wardId', $form);
	$doctors=$('#FormDirectionForPatientAdd_doctorDestId',$form);
	$enterprises.change(function(e){
	
		var value = $(e.currentTarget).val();
		console.debug('change', e, value);
		$list=$wards;
		$list.hide();
		$.ajax({
			url: '/list/wards?enterprise_id=' + value,
			dataType: 'json',
			success: function(data, textStatus, jqXHR){
				console.debug('success',arguments);
				if (data.success == true) {
					var data = data.data;
					console.debug(data);
					var options = '';
					for (var i = 0; i < data.length; i++) {
						var item = data[i];
						options += '<option value="' + item.id + '">' + item.name + '</option>';
					}
					console.debug(options);
					$list.html(options);
					$list.show();
					$list.trigger('change');
				}
			}
		});
	});
	$wards.on('change',function(e){
		console.debug('change');
		$doctors.hide();
		$.ajax({
			url: '/list/doctors?ward_id=' + $(e.currentTarget).val(),
			dataType: 'json',
			success: function(data, textStatus, jqXHR){
				console.debug('success',arguments);
				if (data.success == true) {
					var data = data.data;
					console.debug(data);
					var options = '';
					for (var i = 0; i < data.length; i++) {
						var item = data[i];
						options += '<option value="' + item.id + '">' + item.name + '</option>';
					}
					console.debug(options);
					$doctors.html(options);
					$doctors.show();
					
				}
			}
		});		
		
	});
	
	$('#directionAddSubmit',$form).on('click', function(e){
		var overlay = $('<div>').addClass('overlay');
		$(this).parents('.overlayCont').css({
			'position': 'static'
		}).prepend(overlay);
		
		$.ajax({
			'url': '/hospital/mdirections/add',
			'data': $(this).parents('form').serialize(),
			'cache': false,
			'dataType': 'json',
			'type': 'POST',
			'success': $.proxy(function(data, textStatus, jqXHR){
				if (data.success) {
					overlay.remove();
					$('#directionAddClose',$form).trigger('click', [1]);
				}
			}, this)
		});
	});	
	$('#directionAddClose',$form).on('click', function(e, withRefresh){
		console.debug('click');
		$('.directionsList').removeClass('no-display');
		$('.directionAdd').addClass('no-display');
		if (withRefresh) {
			$('.directionsList').trigger('refresh');
		}
	});	
	
	$enterprises.trigger('change');
	
	
});