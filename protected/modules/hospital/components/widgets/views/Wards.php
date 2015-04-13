<div class="wardsWidget">
    <ul class="filter">
        <li>Палаты
            <ul>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="paidWard" />Платные
                        <span class="tabmark" id="paidWardsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="notPaidWard" />Бесплатные
                        <span class="tabmark" id="notPaidWardsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
            </ul>
        </li>
        <li>Койки
            <ul>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="paidBeds" />Платные
                        <span class="tabmark" id="paidBedsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="notPaidBeds" />Бесплатные
                        <span class="tabmark" id="notPaidBedsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
            </ul>
        </li>
        <li>Тип палаты
            <select class="form-control col-xs-3" id="wardType">
                <option>%any type%</option>
            </select>
        </li>
    </ul>
    <ul class="wardsList">
        <li>
            <?php if($show_settings_icon) { print '<span class="glyphicon glyphicon-cog settings" title="Настройки"></span>'; } ?>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree"><strong class="text-danger">Карантин</strong></span>
        </li>
        <li>
            <?php if($show_settings_icon) { print '<span class="glyphicon glyphicon-cog settings" title="Настройки"></span>'; } ?>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree"><strong class="text-danger">Занята</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li>
            <h4>Палата №1</h4>
            <span class="wardType">Обычная</span>
            <span class="paidType">Платная</span>
            <span class="numFree">Свободно: <strong>3</strong></span>
        </li>
        <li class="new">
            <button class="btn btn-success" title="Добавить новую палату" id="addNewWard">+</button>
        </li>
    </ul>
</div>