[{
    'selector' : '.bed-add:visible',
    'header' : 'Добавление койки',
    'body' : $('<p>').text('С помощью этой кнопки вы можете добавить койку')
},{
    'selector' : '.bed-settings:first',
    'header' : 'Редактирование кровати',
    'body' : $('<p>').text('Иконка шестерёнки даёт вам возможность отредактировать информацию о койко-месте')
}, {
    'selector' : '#helperIcon',
    'header' : 'Закрыть систему подсказок',
    'body' : $('<p>').text('Нажмите на иконку, чтобы открыть или закрыть систему подсказок')
}, {
    'selector' : '.addPatientForm:visible',
    'header' : 'Инфо',
    'body' : $('<p>').text('Не забудьте заполнить информацию о койке!')
}, {
    groupName : 'Обучение фильтрам формы',
    icon : '/images/icons/stock_filters-pop-art_9750.png',
    tooltips : [{
        'selector' : '.wardsWidget .filter:visible',
        'header' : 'Шаг 1',
        'body' : '<p>Измените фильтры</p>',
        'step' : 0,
        'placement' : 'bottom'
    }, {
        'selector': 'li.new:visible',
        'header': 'Шаг 2',
        'body': '<p>Или сбросьте фильтры</p>',
        'step': 1,
        'placement' : 'bottom'
    }, {
        'selector': '#logout-form',
        'header': 'Шаг 3',
        'body': '<p>Или выйдите из аккаунта</p>',
        'step': 2,
        'placement' : 'bottom'
    },{
        'selector': '#bedsstockWardsTab',
        'header': 'Шаг 4',
        'body': '<p>Или что-нибудь ещё</p>',
        'step': 3,
        'placement' : 'bottom'
    },{
        'selector': '#bedsstockExtractsTab',
        'header': 'Шаг 4',
        'body': '<p>Это тоже подсказка</p>',
        'step': 3,
        'placement' : 'bottom'
    }]
}, {
    groupName : 'Обучение пинать',
    icon : '/images/icons/package_network.png',
    tooltips : [{
        'selector' : '.wardType:visible',
        'header' : 'Шаг 1',
        'body' : '<p>Пнули раз</p>',
        'step' : 0,
        'placement' : 'bottom'
    }, {
        'selector': '#bedsstockRelocationsTab',
        'header': 'Шаг 1',
        'body': '<p>И два</p>',
        'step': 0,
        'placement' : 'bottom'
    }, {
        'selector': '#bedsstockPatientsTab',
        'header': 'Шаг 2',
        'body': '<p>А ещё три</p>',
        'step': 1,
        'placement' : 'bottom'
    },{
        'selector': '#bedsstockWardsTab',
        'header': 'Шаг 3',
        'body': '<p>И напоследок</p>',
        'step': 2,
        'placement' : 'bottom'
    }]
}]