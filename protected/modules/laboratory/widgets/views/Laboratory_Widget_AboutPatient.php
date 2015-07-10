<?php
/**
 * @var $this Laboratory_Widget_AboutPatient
 * @var $patient mixed
 * @var int $age
 * @var mixed $medcard
 * @var mixed $patient
 * @var mixed $address
 */
?>
<span class="medcard-info">
	<b>Номер абмулаторной карты:&nbsp;</b> <?= $medcard->card_number ?><br>
	<b>Номер ЭМК:&nbsp;</b> <?= $medcard->mis_medcard ?><br>
	<b>ФИО:&nbsp;</b> <?= $patient->surname." ".$patient->name." ".$patient->patronymic ?>&nbsp;<br>
	<b>Возраст:&nbsp;</b> <?= $age ?>&nbsp;<br>
	<b>Адрес:&nbsp;</b> <?= $address->string ?><br>
	<b>Телефон:&nbsp;</b> <?= $patient->contact ?><br>
	<b>Место работы:&nbsp;</b> <?= $patient->work_place ?>
</span>
