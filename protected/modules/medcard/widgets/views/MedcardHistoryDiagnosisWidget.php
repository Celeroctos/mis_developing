<!-- <div id="accordionD" class="accordion"> -->
<div class="accordion">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseD" data-parent="#accordionD" data-toggle="collapse"
				class="accordion-toggle red-color" data-toggle="tooltip"
				data-placement="right" title="Диагноз приёма"><strong>Диагноз приёма
					(основной и сопутствующие)</strong> </a>
		</div>
		<div class="accordion-body collapse in" id="collapseD">
			<div class="accordion-inner">

				<div class="form-group"">
					<label for="doctor" class="col-xs-3 control-label">Клинический
						диагноз:</label>
					<div class="col-xs-9">
						<p class="form-control-static">
						<?php print ($greeting['note']);	?>
						</p>
						<hr>

						<?php
						print '<ul>';
						foreach($clinSecondary as $item){
							//printf('<p class="form-control-staic">%s</p><br/>',$item['description']);
							printf('<li>%s</li>',$item);
						}
						print '</ul>';
						?>
					</div>
				</div>

				<div class="form-group chooser" id="primaryDiagnosisChooser">
					<label for="doctor" class="col-xs-3 control-label">Основной диагноз
						по МКБ-10:</label>
					<div class="col-xs-9">
					<?php
					foreach($primary as $item){
						printf('<ul><li>%s</li></ul>',$item);
					}
					?>
					</div>
				</div>
				<div class="form-group chooser" id="complicationsDiagnosisChooser">
					<label for="doctor" class="col-xs-3 control-label">Осложнения
						основного диагноза по МКБ-10:</label>
					<div class="col-xs-9">
					<ul>
					<?php
					foreach($complicatingDiag as $item){
						printf('<li>%s</li>',$item);
					}
					?>
					</ul>
					</div>
				</div>
				<div class="form-group chooser" id="secondaryDiagnosisChooser">
					<label for="doctor" class="col-xs-3 control-label">Сопутствующие
						диагнозы по МКБ-10:</label>
					<div class="col-xs-9">
					<ul>
					<?php
					foreach($secondary as $item){
						printf('<li class="form-control-staic">%s</li>',$item);
					}
					?>
					</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
