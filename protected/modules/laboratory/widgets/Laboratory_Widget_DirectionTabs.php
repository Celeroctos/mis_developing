<?php

class Laboratory_Widget_DirectionTabs extends Widget {

    /**
     * @var array with direction statuses to display, where key is status and value is label, by
     *  default it takes statuses from Laboratory_Direction::listStatuses()
     *
     * @see Laboratory_Direction::listStatuses()
     */
    public $statuses = null;

    /**
     * @var string style with nav classes, which describe how to
     *  display tab menu elements
     */
    public $style = TabMenu::STYLE_GREEN;

    /**
     * @var array with columns to display, it passes to grid provider class
     *  of laboratory direction [Laboratory_Grid_Direction]
     */
    public $columns = [
        'id' => '#',
        'surname' => [
            'label' => 'Фамилия',
            'relation' => 'medcard.patient',
        ],
        'name' => [
            'label' => 'Имя',
            'relation' => 'medcard.patient',
        ],
        'patronymic' => [
            'label' => 'Отчество',
            'relation' => 'medcard.patient',
        ],
        'mis_medcard' => [
            'label' => 'ЭМК',
            'relation' => 'medcard',
        ],
        'card_number' => [
            'label' => 'ЛКП',
            'relation' => 'medcard',
        ],
        'analysis_type_id' => [
            'label' => 'Тип анализа',
            'format' => '%{name}',
            'relation' => 'analysis_type',
        ]
    ];

    public function init() {
        if (empty($this->statuses)) {
            $this->statuses = Laboratory_Direction::listStatusesShort();
        }
        foreach ($this->statuses as $status => $label) {
            $this->statuses[$status] = [
                'label' => $label,  'data-tab' => UniqueGenerator::generate('tab'),
            ];
        }
    }

    public function run() {
        $first = array_keys($this->statuses)[0];
        print Html::beginTag('div', [
            'class' => 'col-xs-3 no-padding',
        ]);
        $this->widget('TabMenu', [
            'style' => TabMenu::STYLE_GREEN_STACKED,
            'items' => $this->statuses,
            'active' => $first,
        ]);
        print Html::closeTag('div');
        print Html::beginTag('div', [
            'class' => 'col-xs-9', 'style' => 'padding: 0 0 0 20px',
        ]);
        foreach ($this->statuses as $status => $config) {
            print Html::openTag('div', [ 'id' => $config['data-tab'],
                'style' => $status == $first ? 'display: block;' : 'display: none;',
            ]);
            $this->widget('Panel', [
                'title' => $config['label'],
                'body' => $this->createWidget('GridTable', [
                    'provider' => new Laboratory_Grid_Direction([
                        'columns' => $this->columns,
                        'status' => $status, 'date' => false,
                    ])
                ]),
                'bodyClass' => 'panel-body no-padding',
            ]);
            print Html::closeTag('div');
        }
        print Html::closeTag('div');
    }
}