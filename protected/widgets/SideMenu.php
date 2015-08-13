<?php

class SideMenu extends CWidget {

	/**
	 * @var string - Side menu identification value, by default
	 * 	it uses [mainSideMenu] id for backward compatibility
	 */
	public $id = 'mainSideMenu';

	/**
	 * @var array - Array with side menu items, where key is
	 * 	item's module or controller name and value is array with next structure:
	 *
	 *  + label - Displayable text after image or icon
	 *  + [image] - Name of image file [@see iconPath]
	 *  + [icon] - Name of glyphicon class
	 *  + [items] - Array with sub-items with same structure
	 *  + [href] - Link action with next format {/<module>/<controller>/<action>}
	 *  + [privilege] - Name of privilege
	 *
	 * Despite all fields are not required you can't leave it all empty, cuz
	 * not each parameters compatible with others, for example [icon] and [image]
	 */
	public $items = [
		'reception' => [
			'label' => 'Регистратура',
			'image' => 'register.png',
			'privilege' => 'menuRegister',
			'items' => [
				'search' => [
					'label' => 'Поиск',
					'image' => 'search_patient.png',
					'privilege' => 'menuSearchPatient',
					'href' => '/reception/patient/viewsearch'
				],
				'add' => [
					'label' => 'Регистрация',
					'image' => 'patient_add.png',
					'privilege' => 'menuAddPatient',
					'href' => '/reception/patient/viewadd'
				],
				'write' => [
					'label' => 'Запись',
					'image' => 'write_patient.png',
					'privilege' => 'menuPatientWrite',
					'href' => '/reception/patient/writepatientstepone'
				],
				'rewrite' => [
					'label' => 'Перезапись',
					'image' => 'write_patient.png',
					'privilege' => 'menuPatientRewrite',
					'href' => '/reception/patient/viewrewrite'
				],
				'schedule' => [
					'label' => 'Расписание',
					'image' => 'shedule.png',
					'privilege' => 'menuRaspDoctor',
					'href' => '/reception/shedule/view'
				],
				'reports' => [
					'label' => 'Отчёты',
					'image' => '3.3.jpg',
					'privilege' => 'menuReports',
					'items' => [
						'per-day' => [
							'label' => 'Отчёт за день',
							'privilege' => 'menuReceptionReportsForDay',
							'href' => '/reception/reports/fordayview'
						]
					]
				]
			],
		],
		'call-center' => [
			'label' => 'Call-Центр',
			'image' => 'call_center.png',
			'privilege' => 'menuCallCenter',
			'items' => [
				'write' => [
					'label' => 'Запись',
					'image' => 'write_patient.png',
					'privilege' => 'menuPatientWriteCallCenter',
					'href' => '/reception/patient/writepatientwithoutdata?callcenter=1'
				],
				'change' => [
					'label' => 'Изменение',
					'image' => 'write_patient.png',
					'privilege' => 'menuPatientWriteCallCenter',
					'href' => '/reception/patient/changeordelete?callcenter=1'
				],
			]
		],
		'arm' => [
			'label' => 'Кабинет врача',
			'image' => 'doctors_cabinet.png',
			'privilege' => 'menuArm',
			'items' => [
				'doctor-movement' => [
					'label' => 'Приём',
					'image' => 'greeting_patient.png',
					'privilege' => 'menuDoctorMovement',
					'href' => '/doctors/shedule/view'
				],
				'print-movement' => [
					'label' => 'Печать',
					'image' => 'massprint.png',
					'privilege' => 'menuPrintMovements',
					'href' => '/doctors/print/massprintview'
				],
				'doctor-emk' => [
					'label' => 'Архив приёмов',
					'image' => 'view_medcard.png',
					'privilege' => 'menuDoctorEmk',
					'href' => '/doctors/patient/viewsearch'
				],
			]
		],
		'hospital' => [
			'label' => 'Стационар',
			'image' => 'doctors_cabinet.png',
			'privilege' => 'hospitalMenu',
			'items' => [
				'hospitalization' => [
					'label' => 'Госпитализация',
					'image' => 'greeting_patient.png',
					'href' => '/hospital/hospitalization/view'
				],
				'stock' => [
					'label' => 'Коечный фонд',
					'image' => 'greeting_patient.png',
					'href' => '/hospital/bedsstock/view'
				],
                'arm-d' => [
                    'label' => 'АРМ врача',
                    'image' => 'greeting_patient.png',
                    'href' => '/hospital/armd/view'
                ],
                'arm-n' => [
                    'label' => 'АРМ медсестры',
                    'image' => 'greeting_patient.png',
                    'href' => '/hospital/armn/view'
                ]
			]
		],
		'laboratory' => [
			'label' => 'Лаборатория',
			'image' => 'laboratory.png',
			'privilege' => 'menuAdmin',
			'items' => [
				'treatment' => [
					'label' => 'Процедурный кабинет',
					'image' => 'injection5.png',
					'href' => '/laboratory/treatment/view'
				],
				'laboratory' => [
					'label' => 'Лаборатория',
					'image' => 'pill.png',
					'href' => '/laboratory/laboratory/view'
				],
				'history' => [
					'label' => 'Архив',
					'image' => 'clinic2.png',
					'href' => '/laboratory/treatment/history'
				],
				'guides' => [
					'label' => 'Справочники',
					'image' => 'write13.png',
					'privilege' => [ 'menuAdmin', 'menuOrgGuides', 'menuGuides' ],
					'href' => '/guides/laboratory/analysistype'
				]
			]
		],
		/* 'medcard' => [
			'label' => 'Медкарты',
			'image' => 'medcard.png',
			'href' => '/medcard/template/test',
		], */
		'statistic' => [
			'label' => 'Статистика',
			'image' => 'stat.png',
			'privilege' => 'menuStat',
			'items' => [
				'tasu-in' => [
					'label' => 'Экспорт',
					'image' => '3.1.jpg',
					'privilege' => 'menuTasuIn',
					'href' => '/admin/tasu/viewin'
				],
				'tasu-out' => [
					'label' => 'Импорт',
					'image' => '3.2.jpg',
					'privilege' => 'menuTasuOut',
					'href' => '/admin/tasu/view'
				],
				'report' => [
					'label' => 'Отчётность',
					'image' => 'icon_sample.png',
					'privilege' => 'menuReport',
					'href' => '/statistic/index/view'
				],
				'history' => [
					'label' => 'Просмотр приёмов',
					'image' => 'icon_sample.png',
					'items' => [
						'history' => [
							'label' => 'История приёмов',
							'image' => 'icon_sample.png',
							'href' => '/statistic/history/view',
						],
						'greetings' => [
							'label' => 'Статистика приёмов',
							'image' => 'icon_sample.png',
							'href' => '/statistic/greetings/view',
						],
						'mis' => [
							'label' => 'Статистика МИС',
							'image' => 'icon_sample.png',
							'href' => '/statistic/mis/view',
						]
					]
				]
			]
		],
		'admin' => [
			'label' => 'Сервис',
			'image' => 'admin.png',
			'privilege' => 'menuAdmin',
			'items' => [
				'org-guides' => [
					'label' => 'Организационные справочники',
					'privilege' => 'menuOrgGuides',
					'items' => [
						'guides' => [
							'label' => 'Справочники',
							'privilege' => 'menuGuides',
							'href' => '/guides/enterprises/view'
						],
						'greetings-rule' => [
							'label' => 'Настройка правил приёма',
							'privilege' => 'menuGreetingRights',
							'href' => '/admin/modules/shedulesettings'
						]
					]
				],
				'admin-arm' => [
					'label' => 'Рабочее место врача',
					'privilege' => 'menuAdminArm',
					'items' => [
						'guides-and-templates' => [
							'label' => 'Шаблоны и справочники',
							'privilege' => 'menuGuidesAndTemplates',
							'items' => [
								'templates' => [
									'label' => 'Шаблоны',
									'privilege' => 'menuAdminGuides',
									'href' => '/admin/templates/view'
								],
								'guides' => [
									'label' => 'Справочники',
									'privilege' => 'menuAdminTemplates',
									'href' => '/admin/guides/allview'
								]
							]
						],
						'diagnosis' => [
							'label' => 'Диагнозы',
							'privilege' => 'menuDiagnosis',
							'items' => [
								'mkb10' => [
									'label' => 'МКБ-10',
									'privilege' => 'menuDiagnosisMkb10',
									'href' => '/admin/diagnosis/mkb10view'
								],
								'likes' => [
									'label' => 'Любимые диагнозы',
									'privilege' => 'menuDiagnosisLikes',
									'href' => '/admin/diagnosis/allview'
								],
								'rasp' => [
									'label' => 'Диагнозы для распределения пациентов',
									'privilege' => 'menuDiagnosisRasp',
									'href' => '/admin/diagnosis/distribview'
								],
								'diagnosis' => [
									'label' => 'Клинические диагнозы',
									'privilege' => 'menuClinicalDiagnosis',
									'href' => '/admin/diagnosis/clinicalview'
								],
							]
						]
					]
				],
				'users' => [
					'label' => 'Пользователи, роли, права',
					'privilege' => 'menuAdminUsers',
					'href' => '/admin/users/view'
				],
				'rest' => [
					'label' => 'Настройка календаря',
					'privilege' => 'menuAdminRest',
					'href' => '/admin/shedule/viewrest'
				],
				'doctor-rasp' => [
					'label' => 'Настройка расписания врачей',
					'privilege' => 'menuAdminDoctorRasp',
					'href' => '/admin/shedule/view'
				],
				'user-profile' => [
					'label' => 'Профиль',
					'privilege' => 'menuUserProfile',
					'href' => '/settings/profile/view'
				],
				'system-settings' => [
					'label' => 'Система',
					'privilege' => 'menuSystemSettings',
					'href' => '/settings/system/view'
				],
				'admin-logs' => [
					'label' => 'Логи',
					'privilege' => 'menuAdminLogs',
					'href' => '/admin/logs/view'
				]
			]
		],
		'vtm' => [
			'label' => 'АС Интеграция-ВТМ',
			'image' => 'vtm.png',
			'href'=>'/settings/system/vtm',
			'privilege' => 'menuRegister'
		]
	];

	/**
	 * @var string - Set relative path to folder
	 * 	with icons images
	 */
	public $iconPath = '/images/icons/';

	/**
	 * Initialize side menu widget
	 */
	public function init() {
		if ($this->controller->getModule() != null) {
			$this->_controller = strtolower($this->controller->getModule()->getId());
		} else {
			$this->_controller = null;
		}
		if ($this->controller->getAction() != null) {
			$this->_action = strtolower($this->controller->getAction()->getId());
		} else {
			$this->_action = $this->controller->defaultAction;
		}
		$this->_request = strtolower(Yii::app()->getRequest()->getRequestUri());
		$this->_module = strtolower($this->controller->getId());
	}

	/**
	 * Run side menu widget
	 */
	public function run() {
		print CHtml::openTag('div', [
			'role' => 'complementary',
			'class' => 'bs-sidebar hidden-print'
		]);
		$this->markActive($this->items, $this->items);
		$this->activateItems();
		$this->renderItems($this->items);
		print CHtml::closeTag('div');
    }

	/**
	 * Find active items and mark it
	 * @param array $items - Array with active fields
	 * @param array $parent -
	 */
	public function markActive(array& $items, array& $parent = null) {
		foreach ($items as &$item) {
			$item['parent'] = &$parent;
			if (isset($item['href']) && !strcasecmp($item['href'], $this->_request)) {
				$this->_active = &$item;
			}
			if (isset($item['items']) && count($item['items']) > 0) {
				$this->markActive($item['items'], $item);
			}
		}
	}

	/**
	 * Activate all menu items and it's parents
	 */
	public function activateItems() {
		$parent = &$this->_active;
		while (isset($parent['parent']) && !empty($parent['parent'])) {
			$parent['active'] = true;
			$parent = &$parent['parent'];
		}
	}

	/**
	 * Render list with items and it's sub-items
	 * @param array $items - Array with items
	 * @param bool $root - Is it root list with items?
	 * @see items
	 */
	public function renderItems($items, $root = true) {
		if ($root) {
			$options = [
				'class' => 'nav sidenav',
				'id' => $this->id
			];
		} else {
			$options = [
				'class' => 'nav'
			];
		}
		print CHtml::openTag('ul', $options);
		foreach ($items as $c => $item) {
			if (isset($item['privilege']) && !$this->checkAccess($item['privilege'])) {
				continue;
			}
			print CHtml::openTag('li', [
				'class' => $item['active'] ? 'active' : null
			]);
			if (isset($item['image'])) {
				$this->renderImage($item);
			} else if (isset($item['icon'])) {
				$this->renderIcon($item);
			} else {
				$this->renderLabel($item);
			}
			if (isset($item['items']) && count($item['items']) > 0) {
				$this->renderItems($item['items'], false);
			}
			print CHtml::closeTag('li');
		}
		print CHtml::closeTag('ul');
	}

	public function renderImage($item) {
		print CHtml::link(CHtml::image($this->iconPath.$item['image'], '', [
			'width' => 32,
			'height' => 32
		]) . $item['label'], [isset($item['href']) ? $item['href'] : '#']);
	}

	public function renderIcon($item) {
	}

	public function renderLabel($item) {
		print CHtml::link($item['label'], [isset($item['href']) ? $item['href'] : '#']);
	}

	/**
	 * Check user's access
	 * @param string|array $privilege - Name of privilege
	 * @return bool - True on success on false on failure
	 */
	public function checkAccess($privilege) {
		if (is_array($privilege)) {
			foreach ($privilege as $p) {
				if (!$this->checkAccess($p)) {
					return false;
				}
			}
			return true;
		} else {
			return Yii::app()->{'user'}->{'checkAccess'}($privilege);
		}
	}

	private $_module;
	private $_controller;
	private $_action;
	private $_request;
	private $_active;
}