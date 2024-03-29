/**
 * @type {TemplateEngine} - Глобальный объект, предоставляющий API для работы с
 *      TemplateEngine. Сюда можно добавлять любые методы, какие необходимы и
 *      определять их в конце файла.
 */
var TemplateEngine = TemplateEngine || {};

(function(TemplateEngine) {

	"use strict";

	var CATEGORY_STRING_LIMIT = 15;
	var ITEM_LABEL_LIMIT = 10;

	/**
	 * Базовый ассетер, просто выкидывает иссключение по одному или нескольким
	 * условий. Если отправлено только сообщение, то исключение будет выброшено
	 * автоматически
	 * @param [condition] {...Boolean} - Список выражений для проверки
	 * @param [message] {String} - Сообщение с ошибкой
	 */
	var assert = function(message, condition) {
		if (arguments.length <= 0) {
			throw new Error("Assertion Failed : \"?\"");
		} else {
			if (arguments.length == 1) {
				if (typeof arguments[0] === "string") {
					throw new Error("Assertion Failed : " + message);
				} else if (arguments[0] !== true) {
					throw new Error("Assertion Failed : \"?\"");
				}
			}
			for (var i in arguments) {
				if (!arguments.hasOwnProperty(i)) {
					continue;
				}
				if (arguments[i] !== true && i != arguments.length - 1) {
					throw new Error("Assertion Failed : " + message);
				}
			}
		}
	};

	/**
	 * Функция для расширения прототипов двух классов
	 * @param destination - Расширяемый класс
	 * @param source - Наследуемый класс
	 */
	var extend = function(destination, source) {
		return $.extend(destination.prototype, source.prototype);
	};

	/**
	 * Клонировать объект JavaScript (обычные JSON объекты)
	 * @param source {{}} - Сам объект для клонирования
	 * @returns {{}} - Клон переданного объекта
	 */
	var clone = function(source) {
		return $.extend(true, {}, source);
	};

	/**
	 * Класс узла отвечает за хранение информации о родителях узла, его детях
	 * и реализует всю логику работы дерева. Параметр индекс родителя указывает
	 * на индекс элемента в родителе (используется для быстрой адресации узлов)
	 * @param [parent] {Node|null|undefined} - Родитель создаваемого узла, может быть null
	 * @constructor - Конструктор с одним параметром (родитель)
	 */
	var Node = function(parent) {
		this._parentNode = parent || null;
		this._childrenNode = [];
		this._parentIndex = -1;
	};

	/**
	 * Привязывает узел к другому узлу и устанавливает зависимости
	 * между ребенком и родителем
	 * @param node {Node} - Узел, который нужно привязать
	 * @returns {Boolean} - Возвращает true, если узел был успешно добавлен, иначе
	 *      false, что говорит о том, смешение индекса потомка не было установлено
	 */
	Node.prototype.append = function(node) {
		if (this === node || !node) {
			return false;
		}
		if (this.contains(node)) {
			return false;
		}
		if (node._parentNode) {
			node.remove();
		}
		node._parentNode = this;
		return (
				node._parentIndex = this._childrenNode
					.push(node) - 1
			) !== -1;
	};

	/**
	 * Проверить существование узла в другом элементе, если
	 * индексы потомков и значения ссылок совпадают, то проверка
	 * произойдет быстро, иначе придется перебрать весь массив
	 * @param node {Node} - Узел, который проверяем
	 * @returns {Boolean} - Возвращает true, если потомок существует
	 *      в текущем родителе
	 */
	Node.prototype.contains = function(node) {
		if (!node) {
			return false;
		}
		if (node._parentIndex !== -1 && this._childrenNode[node._parentIndex] == node) {
			return true;
		}
		for (var i in this._childrenNode) {
			if (this._childrenNode[i] && node === this._childrenNode[i]) {
				return true;
			}
		}
		return false;
	};

	/**
	 * Удаляет узел из текущего узла. Удаление узла происходит без смешения
	 * индекса остальных узлов, сделано для повышения производительности работы
	 * с деревьями, потому что адресация к узлам дерева будет происходить со
	 * сложностью O(1), а не O(N)
	 * @param [node] {Node|undefined} - Узел для удаления, если параметр
	 *      не указан, то будет удалет текущий изел из его родителя, если
	 *      такой имеется, конечно, иначе затрется весь узел со всеми его
	 *      потомками, потому что существование такого узла не имеет
	 *      смысла, т.к все зависимости между узлами будет потеряны
	 * @returns {Boolean} - True если был успешно удален
	 */
	Node.prototype.remove = function(node) {
		var i = 0;
		if (node === undefined) {
			if (this._parentNode !== null) {
				return Node.prototype.remove.call(this._parentNode, this);
			} else {
				this.truncate();
			}
			return true;
		}
		if (!this.contains(node)) {
			return false;
		}
		if (node.index() !== -1) {
			if (this._childrenNode[node.index()] === node) {
				this._childrenNode[node.index()] = undefined;
				return true;
			} else {
				for (i in this._childrenNode) {
					if (!this._childrenNode.hasOwnProperty(i)) {
						continue;
					}
					if (node === this._childrenNode[i]) {
						this._childrenNode[i] = undefined;
						return true;
					}
				}
			}
		} else {
			for (i in this._childrenNode) {
				if (!this._childrenNode.hasOwnProperty(i)) {
					continue;
				}
				if (node === this._childrenNode[i]) {
					this._childrenNode[i] = undefined;
					return true;
				}
			}
		}
		return false;
	};

	/**
	 * Зачистка узла - осуществляет удаление текущего узла со
	 * всеми его потомками
	 */
	Node.prototype.truncate = function() {
		for (var i in this._childrenNode) {
			this._childrenNode[i].truncate();
		}
		if (this._parentNode !== null) {
			this._parentNode.remove(this);
		}
		this._childrenNode = [];
	};

	/**
	 * Устанавливает или получает индекс в родительском узле
	 * @param [index] {Number} - Необзательный параметр, но если установлен,
	 *      то индекс узла будет смешен (в этом случае необходимо сделать
	 *      пересчет всех элементов, иначе адресация элементов будет происходить
	 *      со сложность О(N), а не О(1))
	 * @returns {Number} - Возвращает текущий индекс узла в родительском, если
	 *      родитель не имеется, то будет возвращено -1
	 */
	Node.prototype.index = function(index) {
		if (index === undefined) {
			if (this._parentIndex < 0) {
				return this._childrenNode.length;
			} else {
				return this._parentIndex;
			}
		} else {
			this._parentIndex = index;
		}
		return this._parentIndex;
	};

	/**
	 * Устанавливает или получает текущего родителя узла
	 * @param [parent] {Node} - Если родительский узел указан, то будет
	 *      установлен иначе отработает как обычный геттер
	 * @returns {Node|null} - Возвращает текущий или только что установленный
	 *      родительский узел
	 */
	Node.prototype.parent = function(parent) {
		if (parent !== undefined) {
			if (this._parentNode && this._parentNode !== parent) {
				this._parentNode.remove(this);
			}
			this._parentNode = parent;
		} else {
			return this._parentNode;
		}
	};

	/**
	 * Возвращает массив со всеми потомками узла или, если индекс установлен, то
	 * ребенка узла по его индекса
	 * @param [index] {Number} - Индекс потомка
	 * @returns {Array|Node} - Массив с узлами или узел
	 */
	Node.prototype.children = function(index) {
		if (index != undefined) {
			return this._childrenNode[index];
		}
		return this._childrenNode;
	};

	/**
	 * Отсортировать элементы по их индексам и пересчитать смешения, после
	 * чего будет выполнено удаление всех пустых элементов
	 * @param callback - Обратный вызов для сортировки
	 */
	Node.prototype.sort = function(callback) {
		this._childrenNode.sort(callback);
		for (var i in this._childrenNode) {
			if (!this._childrenNode[i]) {
				continue;
			}
			this._childrenNode[i].index(+i);
		}
		var total = this.count();
		this._childrenNode.splice(total, this._childrenNode.length - total);
	};

	/**
	 * Подсчитать общее количество потомков в узле (не учитывает элементы
	 * undefined). Использовать вместо <code>node.children().length</code>
	 * @returns {Number} - Количество потомков
	 */
	Node.prototype.count = function() {
		var totalDefined = 0;
		for (var i in this._childrenNode) {
			if (!this._childrenNode[i]) {
				continue;
			}
			++totalDefined;
		}
		return totalDefined;
	};

	/**
	 * Возвращет предыдущий узел от текущего узла. Функция должна отрабатывать
	 * достаточно быстро за счет индексации элементов, но при нарушении придется
	 * перебирать весь массив
	 * @returns {Node|null} - Предыдущий узел или null
	 */
	Node.prototype.previous = function() {
		if (!this._parentNode) {
			return null;
		}
		if (this._parentNode._childrenNode[this._parentIndex] == this) {
			if (this._parentIndex > 0) {
				var j = this._parentIndex - 1;
				while (!this._parentNode._childrenNode[j--] && j > 0) {
				}
				return this._parentNode._childrenNode[j + 1];
			}
			return null;
		}
		for (var i in this._parentNode._childrenNode) {
			if (this._parentNode._childrenNode[i] != this || !this._parentNode._childrenNode[i]) {
				continue;
			}
			if (i > 0) {
				return this._parentNode._childrenNode[i - 1];
			}
			return null;
		}
	};

	/**
	 * Класс, реализующий базовый функционал для работы с моделями CActiveForm
	 * и другими, в частности отвечает за преобразование имен из обычной нотации
	 * таблиц к нотации JavaScript и создает готовые формы для отправки запросов
	 * @constructor - Ничего не принимает
	 * @param [fields] {String} - Строка, в которой (через запятую) указываются
	 *      названия всех полей столбцов таблицы
	 */
	var FormModelManager = function(fields) {
		if (arguments.length > 0) {
			this._fieldMap = this.add(fields || "");
		} else {
			this._fieldMap = {};
		}
		this._form = null;
	};

	/**
	 * Статический метод, преобразует имя из обычной нотации названия столбца
	 * в таблице базы данных к нотации JavaScript, например столбец "parent_id"
	 * будет преобразован к "parentId" и т.д.
	 * @static - Статический метод, не может имеет контекста "this"
	 * @param name {String} - Название столбца таблицы, например "parent_id"
	 * @returns {String} - Преобразованную стороку к JavaScript нотации
	 */
	FormModelManager.convertField = function(name) {
		var words = name.split("_");
		var result = "";
		for (var i in words) {
			if (i > 0) {
				result += words[i].charAt(0).toUpperCase() + words[i].substr(1);
			} else {
				result = words[i];
			}
		}
		return result;
	};

	/**
	 * Удалить все элементы из менеджера моделей формы
	 */
	FormModelManager.prototype.clear = function() {
		this._fieldMap = {};
	};

	/**
	 * Добавить новый элементы или элементы, добавляются как срока
	 * разделенные запятой. Если перед именем элемента указать
	 * символ /, то он по умолчанию получит модификатор "hidden"
	 * @param fields {String} - Строка, в которой (через запятую) указываются
	 *      названия всех полей столбцов таблицы
	 * @returns {{}} - Созданных массив со всеми элементами
	 */
	FormModelManager.prototype.add = function(fields) {
		this._fieldMap = this._fieldMap || {};
		var fieldArray = fields.split(",");
		for (var i in fieldArray) {
			var native = fieldArray[i].trim();
			var hidden = false;
			if (native[0] == '/') {
				native = native.substr(1);
				hidden = true;
			}
			this._fieldMap[native] = {
				native: native,
				name: FormModelManager.convertField(native),
				hidden: hidden
			};
		}
		return this._fieldMap;
	};

	/**
	 * Удалить элементы из менеджера, удаляются таким же образом
	 * как и добавляются (список имен столбцов указывается через
	 * запятую)
	 * @param fields {String} - Строка, в которой (через запятую) указываются
	 *      названия всех полей столбцов таблицы
	 * @returns {{}} - Созданных массив со всеми элементами
	 */
	FormModelManager.prototype.remove = function(fields) {
		this._fieldMap = this._fieldMap || {};
		var fieldArray = fields.split(",");
		for (var i in fieldArray) {
			var native = fieldArray[i].trim();
			if (!this._fieldMap[native]) {
				continue;
			}
			this._fieldMap.splice(native, 1);
		}
		return this._fieldMap;
	};

	/**
	 * Возвращает список всех зарегестрированных полей
	 * @param [index] - Если индекс не указан, то возвращается массив со
	 *      всеми элементами, иначе значение элемента по индексу
	 * @returns {{}|*} - Массив со всеми элементами или элемент по индексу
	 */
	FormModelManager.prototype.fields = function(index) {
		if (arguments.length > 0) {
			if (this._fieldMap) {
				return this._fieldMap[index];
			} else {
				return null;
			}
		} else {
			return this._fieldMap || [];
		}
	};

	/**
	 * Привязывает созданные переменные к существующей форме через селектор
	 * @param form {jQuery} - Селектор формы для привязки
	 * @param [setField] {Function} - Функция, которая прячет объект, принимает
	 *      текущее поле и инофрмацию о поле
	 */
	FormModelManager.prototype.invoke = function(form, setField) {
		var fields = this.fields();
		this._form = form;
		for(var key in fields) {
			var formField = form.find('#' + fields[key].name);
			if (setField && formField.length) {
				var v = setField(
					formField, fields[key]
				);
				if (v) {
					formField.val(v);
				}
			}
		}
	};

	/**
	 * Возвращает текущую форму
	 * @returns {jQuery}
	 */
	FormModelManager.prototype.form = function() {
		return this._form;
	};

	/**
	 * Конструктор модели, принимает текущий объект с данными об компоненте, где
	 * модель объекта - это структура, хранящая соотвествие элемента и его таблицы
	 * в БД со всеми его полями
	 * @param model {{}} - Модель для элемента
	 * @constructor
	 */
	var Model = function(model) {
		this._model = clone(model || this.defaults());
		this._native = clone(model || this.defaults());
	};

	/**
	 * Абстрактный метод, который устанавливает модель по умолчанию (сейчас нигде не используется,
	 * но может использоваться для значений по умолчанию)
	 */
	Model.prototype.defaults = function() {
		throw new Error("Model/defaults() : \"You must override 'defaults' method\"");
	};

	/**
	 * Устанавливаем новую модель или получаем старую
	 * @param [model] {{}} - Новая модель
	 * @param [native] {Boolean} - Если влаг установлен, то модель
	 *      также будет склонирована в нативную модель
	 * @returns {*} - Только что установленную модель или новую
	 */
	Model.prototype.model = function(model, native) {
		if (model !== undefined) {
			if (native) {
				this._native = clone(model);
			}
			this._model = clone(model);
		}
		return this._model;
	};

	/**
	 * Сравнивает, в зависимости от типа класса, поля позиции и родительского
	 * элемента текущей модели и нативной
	 * @returns {boolean} - Возвращает true
	 */
	Model.prototype.compare = function() {
		// if native model is undefined
		if (this._native && this._model) {
			if (!this.has("id")) {
				return false;
			}
			if (this instanceof Item) {
				return this._native["position"] == this._model["position"] &&
					this._native["categorie_id"] == this._model["categorie_id"];
			}
			return this._native["position"] == this._model["position"] &&
				this._native["parent_id"] == this._model["parent_id"];
		} else {
			return true;
		}
	};

	/**
	 * Возвращает длину текущей модели
	 * @returns {Number} - Длина модели
	 */
	Model.prototype.length = function() {
		if (this._model["id"] === undefined) {
			return 0;
		}
		return Object.keys(this.model()).length;
	};

	/**
	 * Обновляет данные по url. Отправляет запрос, получает данные,
	 * проверяет флаг <code>success</code>, обновляет нативную модель
	 * комопнента и обновляет
	 * @param url
	 * @param sync
	 */
	Model.prototype.fetch = function(url, sync) {
		// create this closure
		var that = this;
		// on success event
		var hook = function(data) {
			// check data for success and terminate execution
			// if we have any errors
			if(data.success != true) {
				console.log(data); return false;
			}
			// update component's model
			that.model(data.data, true);
			// update element
			that.update();
		};
		// send ajax request
		$.ajax({
			'url' : url,
			'cache' : false,
			'dataType' : 'json',
			'type' : 'GET',
			'sync' : sync || false,
			'success' : hook
		});
	};

	/**
	 * Возвращает или устанавливает поле для текущей модели
	 * @param field {String} - Название поля
	 * @param [value] {*} - Значение поля
	 * @returns {*} - Возвращает текущее или только установленное
	 *      значение поля
	 */
	Model.prototype.field = function(field, value) {
		if (value !== undefined) {
			this._model[field] = value;
		}
		if (this._model[field] === undefined) {
			throw new Error("Model/field() : \"Field (" + field + ") hasn't been declared in model\"");
		}
		return this._model[field];
	};

	/**
	 * Возвращает или устанавливает поле для нативной модели
	 * @param field {String} - Название поля
	 * @param [value] {*} - Значение поля
	 * @returns {*} - Установленное или текущее значение поля
	 */
	Model.prototype.native = function(field, value) {
		if (value !== undefined) {
			this._native[field] = value;
		}
		if (this._native[field] === undefined) {
			throw new Error("Model/native() : \"Field (" + field + ") hasn't been declared in model\"");
		}
		return this._native[field];
	};

	/**
	 * Проверяет соответствие полей в исходной моделе и установленной
	 * @param field {String} - Название поля для проверки
	 * @returns {boolean} - Возвращет true, если поля совпадают в исходной и
	 *      установленной модели
	 */
	Model.prototype.test = function(field) {
		if (!this._model[field] || !this._native[field]) {
			return false;
		}
		return this._model[field] == this._native[field];
	};

	/**
	 * Проверяет текущую модель элемента на наличие поля, потому
	 * что функция <code>Model.field</code> выкидывает исключение,
	 * если поле с таким элементом не существует
	 * @param field {String} - Поле для проверки
	 * @returns {boolean}
	 */
	Model.prototype.has = function(field) {
		return this._model[field] != undefined;
	};

	/**
	 * Класс селектора хранит в себе объект jQuery и реализует
	 * базовые операции для работы с ним
	 * @param [selector] {jQuery} - Принимает объект jQuery, если последний будет
	 *      null или undefined, то выкинет исключение
	 * @constructor - Конструкирует обретку над объектом jQuery
	 */
	var Selectable = function(selector) {
		Selectable.prototype.selector.call(this, selector || assert("Selector can't be null or undefined"));
	};

	/**
	 * Возвращает или получает текущий объект jQuery
	 * @param [selector] {jQuery} - Объект для установки (опционально)
	 * @returns {jQuery} - Текущий или только что устаноелнный объект
	 */
	Selectable.prototype.selector = function(selector) {
		if (selector !== undefined) {
			if (!selector.data("instance")) {
				selector.data("instance", this);
			}
			this._jqSelector = selector;
		}
		return this._jqSelector;
	};

	/**
	 * Привязывает один селектор к другому, при условии, что
	 * они уже не входят друг в друга
	 * @param selectable {Selectable} - Потомок для добавления
	 */
	Selectable.prototype.append = function(selectable) {
		if (!$.contains(this._jqSelector, selectable._jqSelector)) {
			this._jqSelector.append(selectable._jqSelector);
		}
	};

	/**
	 * Удаляет текущий селектор из родительского, если объект
	 * для удаления не указан, то будет удален текущий узел
	 * из родительского
	 * @param [selectable] {Selectable} - Селектор на удаление
	 */
	Selectable.prototype.remove = function(selectable) {
		if (selectable == undefined) {
			Selectable.prototype.remove.call(this.parent(), this);
		} else {
			if ($.contains(this._jqSelector, selectable._jqSelector)) {
				this._jqSelector.detach(selectable._jqSelector);
			}
		}
	};

	/**
	 * Данный интерфейс позволяет реализовать возможность компоненту
	 * быть передвигаемым
	 * @constructor - Ничего не делает
	 */
	var Draggable = function() {
	};

	/**
	 * Отвечает за инициализацию передвижения объекта
	 */
	Draggable.prototype.drag = function() {
		assert("Draggable/drag() : \"You must implement 'drag' method\"");
	};

	/**
	 * Данный интерфейс позволяет реализовать возможность компоненты
	 * бросать в него другие объекты
	 * @constructor - Ничего не делает
	 */
	var Droppable = function() {
	};

	/**
	 * Отвечает за инициализацию бросаемого процесса
	 */
	Droppable.prototype.drop = function() {
		assert("Droppable/drop() : \"You must implement 'drop' method\"");
	};

	/**
	 * Менеджер запросов - абстрактный класс, который хранит в себе
	 * менеджер модели для инициалзиации формы и обязует компонент
	 * реализовать базовые методы для работы с запросами (запись,
	 * обновление, удаление, обновление, получение)
	 * @param fields {String} - Список поле для менеджера форм (строка с полями их БД)
	 * @constructor - Инициализирует менеджер формы CActiveForm
	 */
	var RequestManager = function(fields) {
		this._manager = new FormModelManager(fields);
	};

	/**
	 * Возвращает менеджер для текущего компонента
	 * @returns {FormModelManager} - Менеджер компонента
	 */
	RequestManager.prototype.manager = function() {
		return this._manager;
	};

	/**
	 * Этот метод отвечает за сохранение копонента, вызывается после
	 * нажатия на кнопку "Сохранить" (дискета)
	 */
	RequestManager.prototype.write = function() {
		assert("RequestManager/save() : \"You must implement 'save' method\"");
	};

	/**
	 * Этот метод отвечает за редактирование компонента, вызывается
	 * после нажатия на кнопку "Редактировать" (карандаш)
	 */
	RequestManager.prototype.edit = function() {
		assert("RequestManager/edit() : \"You must implement 'edit' method\"");
	};

	/**
	 * Этот метод отвечает за удаление компонента, вызывается после
	 * нажатия на кнопку "Удалить" (крестик)
	 */
	RequestManager.prototype.erase = function() {
		assert("RequestManager/erase() : \"You must implement 'erase' method\"");
	};

	/**
	 * Этот метод отвечает за обновление формы компонента (не помню точно), вроде,
	 * нигде не исползуется, но не уверен
	 */
	RequestManager.prototype.refresh = function() {
		assert("RequestManager/refresh() : \"You must implement 'refresh' method\"");
	};

	/**
	 * Не используется
	 */
	RequestManager.prototype.read = function() {
		assert("RequestManager/read() : \"You must implement 'read' method\"");
	};

	/**
	 * Просто вспомогательный рендер с базовыми методами для рендера базовых
	 * элементов компонетов, таких как кнопки сохранения, редактирование,
	 * удаление или загрузки. Вроде, так и не был полностью слит с компонентами. Не
	 * знаю почему он называется Factory, а не просто ComponentRenderer
	 *
	 * @type {{
     *      renderSaveButton: Function,
     *      renderDeleteButton: Function,
     *      renderLinkButton: Function,
     *      renderLoadingImage: Function
     * }}
	 */
	var RenderFactory = {
		renderSaveButton: function(style) {
			var me = this;
			return $("<span></span>", {
				class: me.glyphicon(),
				style: style || "margin-right: 5px;"
			}).click(function() {
				if (!me.has("id")) {
					me.write();
				} else {
					me.edit();
				}
			});
		},
		renderDeleteButton: function(style) {
			var that = this;
			return $("<span></span>", {
				class: "glyphicon glyphicon-remove",
				style: style || "margin-right: 5px; margin-left: 3px;"
			}).click(function() {
				if (that instanceof Item && that.category()) {
					that.category().reference(null);
					that.category(null);
					hasChanges = true;
				} else if (that instanceof Category && that.reference()) {
					that.reference().remove();
					hasChanges = true;
				}
				that.remove();
				that.erase();
			});
		},
		renderLinkButton: function(style) {
			var me = this;
			var b = $("<span></span>", {
				class: "glyphicon glyphicon-link",
				style: style || "margin-right: 5px;"
			});
			b.click(function() {
				if (me.parent() instanceof CategoryCollection) {
					return true;
				}
				if (me.reference()) {
					return true;
				}
				var item = new Item(me.parent(), null, null,
					TemplateEngine.getTemplateCollection().find("category")
				);
				item.category(me);
				me.reference(item);
				me.parent().append(item);
				item.update();
				hasChanges = true;
			});
			return b;
		},
		renderLoadingImage: function(style) {
			return $("<img>", {
				src: globalVariables.baseUrl + "/images/ajax-loader.gif",
				style: style || "width: 25px"
			});
		}
	};

	/**
	 * Основной абстрактный класс системы, является конечным компонентов, которые
	 * отображаются в редакторе, имеет 2 абстрактных метода для рендера элемента
	 * и его клонирования (не используется, кажется)
	 *
	 * @param parent {Node} - Родительский компонент, может быть как узлом, так и компонентом,
	 *      так и элементов или категорией
	 * @param model {{}} - У каждого компонента должна быть модель - это слепок элемента из БД,
	 *      после изменения которого вызываются метода из RequestManager и обновляют данные из этой модели
	 * @param selector {jQuery} - Селектор, к которому привязан объект, по хорошему нужно было делать
	 *      виртуальный DOM, а то уж слишком много затрат по производительности на работу с деревом
	 * @param fields {String} - Строка, в которой через запятую перечисляются все элементы из БД для
	 *      менеджера форм, он переведет их в нотацию JS и будет использовать для заполнения ID полей формы
	 * @constructor - Базовый конструктор для копонента
	 */
	var Component = function(parent, model, selector, fields) {
		// Construct parent classes
		Model.call(this, model);
		Node.call(this, parent);
		Selectable.call(this, selector || this.render());
		RequestManager.call(this, fields);
		// Fix for $.extend, can't check instance
		if (this.drag) {
			this.drag();
		}
		if (this.drop) {
			this.drop();
		}
		// Default component model's position is 1
		if (!this.has("position")) {
			this.field("position", 1);
		}
	};

	extend(Component, Node);
	extend(Component, Selectable);
	extend(Component, Model);
	extend(Component, RequestManager);

	/**
	 * Метод переопределяется каждым адаптером копонента и возвращает
	 * объект jQuery (в нем нужно рендерить сам копонент и брать данные
	 * для отображения из модели, если такие поля имеются, конечно)
	 */
	Component.prototype.render = function() {
		assert("Component/render() : \"You must override 'render' method\"");
	};

	/**
	 * Метод отвечает за клонирование копонента, не помню, но, кажется, где-то
	 * исползовался, но лучше его оставить. Конечно, можно сделать грамотную зачистку кода,
	 * но времени на это особо нет
	 * @param [parent] {Component} - Родительский элемент
	 * @param [model] {{}} - Модель по умолчанию при клонировании
	 * @param [selector] {jQuery} - Селектор элемента
	 */
	Component.prototype.clone = function(parent, model, selector) {
		assert("Component/clone() : \"You must implements 'clone' method\"");
	};

	/**
	 * Поиск элемента по его пути (путь - это какая-то хрень, сделанная кем-то для
	 * "оптимизации" и "облегчения" процесса сортировки элементов, из-за которой я потратил
	 * дохрена времени, чтобы засунуть её в дизайнер)
	 * @param path {String} - Путь для поиска
	 * @returns {Component} - Найденный компонент или null
	 */
	Component.prototype.findByPath = function(path) {
		if (this.has("path") && this.field("path") == path) {
			return this;
		}
		for (var i in this.children()) {
			if (!this.children(i)) {
				continue;
			}
			var found = this.children(i).findByPath(path);
			if (found) {
				return found;
			}
		}
		return null;
	};

	/**
	 * Примитивный класс, который возвращает тип иконки для отобраджения, в зависимости
	 * от текущего состояния компонента, т.е если у нас имеется поле "id" в модели, значит
	 * элемент уже сохранен в базе и нужно отображать кнопку редактировать, иначе отображать
	 * кнопку редактировать. Странно, почему нельзя было просто отображать готовый элемент, а
	 * не плодить одинаковые условия?
	 * @returns {string} - Класс для иконки
	 */
	Component.prototype.glyphicon = function() {
		if (!this.length() || !this.has("id")) {
			return "glyphicon glyphicon-floppy-save"
		} else {
			return "glyphicon glyphicon-pencil";
		}
	};

	/**
	 * Очень важный метод, который отвечает за пересчет всех позиций всех его элементов и
	 * его потомков (опционально)
	 * @param depth {Boolean} - Если установлено на True, то также будут пересчитаны все потомки
	 * @returns {Boolean} -  Вообще, ничего не должно возвращать, но венет или False
	 *      или Undefined, видимо, было лень написать void 0
	 */
	Component.prototype.compute = function(depth) {
		// get parent selector
		var parent = this.selector();
		// default index is 1 (not null)
		var index = 1;
		// update all indexes
		if (this instanceof CategoryCollection) {
			parent.children(".template-engine-nestable").children(".template-engine-list").children(".template-engine-category").each(function(i, child) {
				$(child).data("instance").field("position", index++);
			});
		} else {
			parent.children(".template-engine-list").children(".template-engine-category").each(function(i, child) {
				// get category instance
				var category = $(child).data("instance");
				// if category hasn't reference then recompute it
				if (!category.reference()) {
					category.field("position", index++);
				}
			});
			parent.children(".template-engine-items").children(".template-engine-item").each(function(i, child) {
				$(child).data("instance").field("position", index++);
			});
		}
		// reorder children with new positions
		this.sort(function(left, right) {
			return +left.field("position") - +right.field("position");
		});
		// update item instance only after order
		this.update();
		// if we have set depth flag, set recompute positions
		// for every category in tree
		if (!depth) {
			return false;
		}
		for (var i in this.children()) {
			if (!this.children(i)) {
				continue;
			}
			if (this.children(i) instanceof Category) {
				this.children(i).compute(
					true
				);
			}
		}
	};

	/**
	 * Удалет все из текущего компонента и добавляет загрузку
	 */
	Component.prototype.loading = function() {
		this.selector().empty().append(
			$("<div></div>", {
				style: "text-align: center"
			}).append(
				RenderFactory.renderLoadingImage(
					"width: 35px"
				)
			)
		);
	};

	/**
	 * Рендерит кнопку для редактирования компонента
	 * @param style {String} - Стиль с разными фиксами
	 * @returns {jQuery} - Объект jQuery
	 * @private
	 */
	Component.prototype._renderEditButton = function(style) {
		var me = this;
		return $("<span></span>", {
			class: me.glyphicon(),
			style: style || "margin-right: 5px;"
		}).click(function() {
			if (!me.has("id")) {
				me.write();
			} else {
				me.edit();
			}
		});
	};

	/**
	 * Судя по всему, нигде не исползуется, но должна считать
	 * количество активных узлов для этого элемента и всех его потомков. Эм
	 * это очень странный метод и делает он тоже что-то очень странное, нужно
	 * будет его удалить, оставлю TD
	 * @returns {Number} - Количество активных узлом
	 * TODO: 'Delete Me'
	 */
	Component.prototype.countActiveNodes = function() {
		var count = this.has("id") ? this.field("id") : 0;
		for (var i in this.children()) {
			if (!this.children(i)) {
				continue;
			}
			count += this.children(i).count();
		}
		return count;
	};

	/**
	 * Рендерить кнопку для удаления, опять старый стиль, потому что
	 * уже есть класс для рендера базовых элементов, но так просто заменить
	 * нельзя, потому что уже реализовано поведение для всех типов компонентов
	 * @param style {String} - Стиль кнопки, чтобы пофиксить отображение
	 * @returns {jQuery} - Объект jQuery с кнопкой
	 * @private
	 */
	Component.prototype._renderRemoveButton = function(style) {
		var that = this;
		return $("<span></span>", {
			class: "glyphicon glyphicon-remove",
			style: style || "margin-right: 5px; margin-left: 3px;"
		}).click(function() {
			if (that instanceof Item && that.category()) {
				that.category().reference(null);
				that.category(null);
				hasChanges = true;
			} else if (that instanceof Category) {
				if (that.count() > 0 && !confirm("Категория имеет элементы или подкатегории, шаблон будет расформирован. Продолжить?")) {
					return true;
				}
				if (that.reference()) {
					that.reference().remove();
				}
				hasChanges = true;
			}
			that.remove();
			that.erase();
		});
	};

	/**
	 * Метод возвращает модель по умолчанию для компонента, по умолчанию
	 * возвращает пустую модель. Раньше использовалась, сейчас нет
	 * @returns {{}} - Модель по умолчанию
	 */
	Component.prototype.defaults = function() {
		return {};
	};

	/**
	 * Обновление компонента, просто рендерит текущий компонент
	 * и заменяет текущий новым и устанавливает все базовые поля
	 */
	Component.prototype.update = function() {
		var s = this.render().data("instance", this);
		this.selector().replaceWith(s);
		this.selector(s);
	};

	/**
	 * Переопределенный метод для добавления вложенных компонентов,
	 * реализуется для каждого адаптера компонента по своему, потому
	 * что добавлять нужно в разные контейнеры. Просто объединяет в
	 * себе добавление к узлам и добавление к DOM
	 * @param element {Component} - Элемент, который нужно добавить
	 */
	Component.prototype.append = function(element) {
		if (this === element) {
			return false;
		}
		if (Node.prototype.append.call(this, element)) {
			Selectable.prototype.append.call(this, element);
		}
		if (!element.parent()) {
			element.parent(this);
		}
		return true;
	};

	/**
	 * Тоже самое, что и для добавления, только реализует удаление
	 * из родительского узла и из DOM
	 * @param [element] {Component} - Элемент для удаления
	 */
	Component.prototype.remove = function(element) {
		if (this === element) {
			return false;
		}
		if (Node.prototype.remove.call(this, element)) {
			if (element != undefined) {
				element.selector().detach();
			} else {
				this.selector().detach();
			}
		}
		return true;
	};

	/**
	 * Шаблон - один из видов компонентов, позволяет хранить в себе шаблон
	 * для рендера элемента, по сути хранит модель по умолчанию для какого-то
	 * типа элементов (Item), в графическом предсталвении отображается справа
	 * от общего редактора в отдельном контейнере
	 * @param collection {Component} - Коллекция, в которой хранится шаблон (TemplateCollection)
	 * @param key {String} - Уникальный ключ шаблона
	 * @param title {String} - Заголовок шаблона
	 * @param id {Number} - Идиотский ключ, взятый откуда-то из какой-то модели на
	 *      серверной стороне, просто программисты, которые ее писали не додумались
	 *      использовать текст как идентификатор для элемента
	 * @constructor
	 * @see Component
	 * @see Draggable
	 */
	var Template = function(collection, key, title, id) {
		// initialize variables first
		this._title = title;
		this._id = id;
		this._key = key;
		// invoke constructors
		Draggable.call(this);
		Component.call(this, collection, null, null);
	};

	extend(Template, Component);
	extend(Template, Draggable);

	/**
	 * Возвращает текущий идентификатор шаблона
	 * @returns {Number} - Идентификатор шаблона
	 */
	Template.prototype.id = function() {
		return this._id;
	};

	/**
	 * Возвращает заголовок шаблона (его название на Русском)
	 * @returns {String} - Название шаблона
	 */
	Template.prototype.title = function() {
		return this._title;
	};

	/**
	 * Возвращает уникальный ключ шаблона (строка, например, text text-area, number и т.д)
	 * @returns {String} - Ключ шаблона
	 */
	Template.prototype.key = function() {
		return this._key;
	};

	/**
	 * Рендерит шаблон - возвращает контейнер с классом template-engine-item,
	 * стиль которого объявлен в файле "template-engine.css"
	 * @param [title] {String} - Заголовок для отображения (опционально)
	 * @returns {jQuery} - Возвращает объект jQuery с шаблоном
	 * @see Component#render
	 */
	Template.prototype.render = function(title) {
		title = title || this.title();
		if (title.length <= 2) {
			return $("<div></div>", {
				html: "<div>" + title + "</div>",
				class: "template-engine-item",
				style: "cursor: default;" +
				"float: left;" +
				"width: 10px"
			});
		} else {
			return $("<div></div>", {
				html: "<div>" + title + "</div>",
				class: "template-engine-item",
				style: "cursor: default;"
			});
		}
	};

	/**
	 * Шаблон элемента можно перетаскивать, поэтому реализовыванем
	 * перетаскивание в метод "drag"
	 * @see Draggable#drag
	 */
	Template.prototype.drag = function() {
		this.selector().draggable({
			helper: function() {
				return $(this).clone().data("instance",
					$(this).data("instance")
				);
			},
			start: function() {
				$(this).css("visibility", "hidden");
			},
			stop: function() {
				$(this).css("visibility", "visible");
			},
			revert: "invalid"
		}).disableSelection();
	};

	/**
	 * Класс - коллекция для шаблонов, тоже является компонентов, хранит
	 * в себе все шаблоны и отображает их в контейнере справа от редактора
	 * @param [selector] {jQuery} - Контейнер по умолчанию (опционально)
	 * @constructor
	 * @see Component
	 */
	var TemplateCollection = function(selector) {
		Component.call(this, null, null, selector);
	};

	extend(TemplateCollection, Component);

	/**
	 * Осуществляет поиск шаблона в коллекции, основанный на
	 * поиске ключа, а не идентификатора
	 * @param key {String} - Ключ для поиска шаблона
	 * @returns {Template} - Компонент шаблона или выкинет исключение
	 */
	TemplateCollection.prototype.find = function(key) {
		var children = this.children();
		for (var i in children) {
			if (children[i].key() === key) {
				return children[i];
			}
		}
		assert("TemplateCollection/find() : \"Unresolved template key (" + key + ")\"");
	};

	/**
	 * Рендерит компонент для колекции шаблонов
	 * @returns {jQuery} - Конектйнер для шаблонов
	 * @see Component#render
	 */
	TemplateCollection.prototype.render = function() {
		return $("<div></div>", {
			class: "template-engine-template"
		});
	};

	/**
	 * Элемент - первый по важности элемент для отображения, включает в себя
	 * много костылей и тупых тупостей, которые избежать сложно из-за
	 * кривизны серверной архитектуры, отвечает за хранение информации
	 * о моделях всех элементов, которые можно отображать. Каждый элемент
	 * должен иметь тип данных, который берется из шаблона (см. выше)
	 *
	 * @param [parent] {Item|Node|Component|null|undefined} - Родитель элемента (опционально)
	 * @param [selector] {jQuery|undefined} - Объект jQuery по умолчанию, нужно, только если
	 *      кто-то будет расширяет текущий функционал элемента
	 * @param model {{}} - Модель элемента, которая будет установлена по умолчанию
	 * @param [template] {Template} - Шаблон, на основе котрого будет строиться элемент, по сути
	 *      просто позволяет заполнить значение типа элемента сразу (взять его из шаблона)
	 * @param [category] {Category} - Элемент, может быть не элементов, а может быть ссылкой
	 *      на какую-то категорию (бюыло принято такое решение, чтобы не плодить лишнюю сущность
	 *      класса для ссылки и просто добавить лишнее поле, учитывая жопонутость JavaScript, то
	 *      это вполне оправданное решение)
	 * @constructor
	 * @see Component
	 * @see Template
	 */
	var Item = function(parent, model, selector, template, category) {
		// we need to save template before running render
		this._elementTemplate = template;
		this._category = category;
		// call super constructors
		Component.call(this, parent, model, selector,
			"id," +
			"/type," +
			"/categorie_id," +
			"label," +
			"guide_id," +
			"allow_add," +
			"is_required," +
			"label_after," +
			"size," +
			"is_wrapped," +
			"/position," +
			"config," +
			"default_value," +
			"label_display," +
			"show_dynamic," +
			"hide_label_before"
		);
		// set default element type
		if (template.id()) {
			this.field("type", template.id());
		}
	};

	extend(Item, Component);

	/**
	 * Костыль №1 - очень важный.
	 *
	 * Проблема: нужно отправлять запрос на сервер для получения модели для компонента, а после
	 * получения обновлять компонент, но мы не можем после получения элемента его
	 * обновить, потому что мы не знаем для какого элемента мы отправили запрос.
	 *
	 * Возможное решение: нужно отправлять на сервер вместе с данными для обновления идентификатор
	 * элемента, чтобы после получения ответа грузить элемент из DOM, получать его компонент и
	 * обновлять, но все элементы дерева генерируются в коде и мы не можем хранить для каждого
	 * ID в DOM дереве, поэтому такое решение - не решение
	 *
	 * Возможное решение: нужно строить хеш-сумму на основе позиции в дереве и всех родительских позиций и
	 * отправлять это на сервер и возвращать в ответе, после чего можно будет на основе рутового
	 * узла найти наш необъодимый, при условии, что никто не будет трогать дерево и вносить в
	 * него никакие изменения
	 *
	 * Но: пока оставляем как есть, потому что текущее поведение интерфейса не подрузомевает редактирование
	 * нескольких элементов одновременно, да и проспускная способности сети достаточно выоска, чтобы отправить
	 * запрос и получить, пока пользователь будет добавлять новые данные
	 *
	 * @type {null}
	 */
	Item["-instance"] = null;

	var _updateItem = function(event, data) {
		var json = $.parseJSON(data);
		if (!json.success) {
			// Удаляем предыдущие ошибки
			$('#errorAddElementPopup .modal-body .row p').remove();
			// Вставляем новые
			for (var i in json.errors) {
				for (var j = 0; j < json.errors[i].length; j++) {
					$('#errorAddElementPopup .modal-body .row').append("<p>" + json.errors[i][j] + "</p>")
				}
			}
			$('#errorAddElementPopup').modal();
			return true;
		}
		var model = json["model"];
		if (!model) {
			return true;
		}
		var config = $.parseJSON(model["config"]);
		for (var i in config) {
			model[i] = config[i];
		}
		Item["-instance"].model(model, true);
		Item["-instance"].update();
		hasChanges = true;
	};

	$(document).ready(function() {
		$("#element-add-form").on("success", _updateItem);
		$("#element-edit-form").on("success", _updateItem);
	});

	/**
	 * Метод возвращает текущую категорию, на которую ссылается
	 * этот элемент (только если он является ссылкой на категорию, т.е
	 * отображает серым цветом). Можно как установить новую, так и получить
	 * текущую категорию. Если категория является строкой, то будет
	 * выполнен поиск категории по ее пути (если это путь, иначе будет null)
	 * @param [category] {Category|String} - Категория или путь к категории
	 * @returns {Category|null|undefined} - Текущую категорию, на которую
	 *      ссылается элемент или пусто
	 */
	Item.prototype.category = function(category) {
		if (arguments.length > 0) {
			this._category = category;
		}
		if (typeof this._category == "string") {
			this._category = TemplateEngine.getCategoryCollection()
				.findByPath(this._category);
		}
		return this._category;
	};

	/**
	 * Переопределяет метод FormModelManager для иницилазиции поля
	 * формы. Вся разница заклюачется в том, что элемент еще проверяет
	 * себя на наличие ссылки на категорию и возвращет значения родителя
	 * категории, а не текущего элемента (т.е как-бы позволяет думать,
	 * что элемент - это указатель на категорию). Да - это неправильно,
	 * потому что нужно было создать класс Reference, который бы расширил
	 * класс Item и переопределил метод field, но насрать!
	 * @param key {String} - Ключ из модели
	 * @param [field] {*} - Значение поля
	 * @returns {*} - Значение поля
	 */
	Item.prototype.field = function(key, field) {
		if (this.category()) {
			if (key != "categorie_id") {
				return this.category().field(key, field);
			} else {
				return this.category().field("parent_id", field);
			}
		} else {
			return Model.prototype.field.call(this, key, field);
		}
	};

	/**
	 * Отправляет запрос на сохранение элемента
	 * @param form {jQuery} - Форма с полями
	 * @see RequestManager#write
	 */
	Item.prototype.write = function(form) {
		var me = this;
		// fix item's position
		if (!this.has("position") || !+this.field("position")) {
			this.field("position", 1);
		}
		// fix item's category parent
		if (this.parent().has("id")) {
			this.field("categorie_id", this.parent().field("id"));
		} else {
			this.field("categorie_id", -1);
		}
		// fix: reset all default values
		$("input[id^='defaultValue']").val("");
		// fix: reset all input fields
		$('#addElementPopup form .form-group input').val("");
		// fix: reset all select fields
		$('#addElementPopup form .form-group select').val(0);
		// initialize element form
		this.manager().invoke($('#addElementPopup form'),
			function(field, info) {
				if (info.hidden) {
					field.parent(".col-xs-9").parent(".form-group")
						.css("visibility", "hidden")
						.css("position", "absolute");
				}
				if (me.has(info.native)) {
					if (info.native == "default_value") {
						$("input[id^='defaultValue']").val(me.field(info.native));
					}
					return me.field(info.native);
				}
				return null;
			}
		);
		Item["-instance"] = this;
		// set type's value to template's id
		$('#element-add-form select#type').val(this.template().id());
		// invoke trigger to change input fields
		$('#element-add-form select#type').trigger('change');
		// invoke modal window
		$('#addElementPopup').modal({
			backdrop: 'static',
			keyboard: false
		}).draggable("disable");
	};

	/**
	 * Открывает окно для редактирования формы
	 * @see RequestManager#edit
	 */
	Item.prototype.edit = function() {
		var me = this;
		// fix for position
		if (!this.has("position") || !+this.field("position")) {
			this.field("position", 1);
		}
		// fix for parent's category id
		if (this.parent().has("id")) {
			this.field("categorie_id", this.parent().field("id"));
		} else {
			this.field("categorie_id", -1);
		}
		var data = { data: me.model() };
		// disable show dynamic parameter for table (4)
		$('#editElementPopup #showDynamic').prop('disabled', data.data['type'] == 4);
		// reset default value's
		$("input[id^='defaultValue']").val("");
		// initialize form
		this.manager().invoke($('#editElementPopup form'),
			function(field, info) {
				if (info.hidden) {
					field.parent(".col-xs-9").parent(".form-group")
						.css("visibility", "hidden")
						.css("position", "absolute");
				}
				if (info.native == 'showDynamic') {
					console.log(info);
				}
				// Подгрузка значений справочника для дефолтного значения
				if (info.name == 'defaultValue' && (data.data['type'] == 2 || data.data['type'] == 3)) {
					$('select#guideId').trigger('change', [data.data[info.name]]);
					return data.data[info.native];
				}
				if (info.native == "default_value") {
					$("input[id^='defaultValue']").val(me.field(info.native));
				}

				// Таблица
				if (info.name == 'config') {
					if(typeof data.data['config'] != 'object') {
						var config = $.parseJSON(data.data['config']);
					} else {
						var config = data.data['config'];
					}
					if (data.data['type'] == 4) {
						printHeadersTable(config,
							$('#editElementPopup .table-config-headers tbody'),
							$('#editElementPopup .colsHeaders'),
							$('#editElementPopup .rowsHeaders'),
							$('#editElementPopup #numRows'),
							$('#editElementPopup #numCols')
						);
						printDefaultValuesTable(config["numCols"], config["numRows"]);
						if (config.values != undefined && config.values != null) {
							writeDefValuesFromConfig(config.values);
						}
					}
					if (data.data['type'] == 5) {
						$('#editElementPopup').find('#numberFieldMaxValue, #numberFieldMinValue, #numberStep').parents('.form-group').removeClass('no-display');
						$('#editElementPopup #numberFieldMaxValue').val(config["maxValue"]);
						$('#editElementPopup #numberFieldMinValue').val(config["minValue"]);
						$('#editElementPopup #numberStep').val(config.step);
					}
					if (data.data['type'] == 6) {
						$('#editElementPopup').find('#dateFieldMaxValue, #dateFieldMinValue').parents('.form-group').removeClass('no-display');
						if (config != null && config != '') {
							$('#editElementPopup #dateFieldMaxValue').val(config["maxValue"]);
							$('#editElementPopup #dateFieldMinValue').val(config["minValue"]);
						} else {
							// Если конфига нет - надо просто поставить пустое значение
							$('#editElementPopup #dateFieldMaxValue').val('');
							$('#editElementPopup #dateFieldMinValue').val('');
						}
						// Затриггерим контрол, чтобы данные подкачались в видимые поля контрола
						$('#editElementPopup #dateFieldMaxValue').trigger('change');
						$('#editElementPopup #dateFieldMinValue').trigger('change');
					}
					$('#editElementPopup #showDynamic').val(
						config["showDynamic"]
					);
				}
				// Теперь нужно проверить - если взведён флаг "есть зависимость" - нужно выключить некоторые опции в
				//    в изменении типа
				if (data.data["is_dependencies"] == 1) {
					$('#element-edit-form select#type option:not([value=2]):not([value=3])').addClass('no-display');
				} else {
					$('#element-edit-form select#type option').removeClass('no-display');
				}
				$.proxy(me.manager().form().find("select#type").trigger('change'),
					me.manager().form().find("select#type")
				);
				return data.data[info.native];
			}
		);
		// fix fix fix
		Item["-instance"] = this;
		// set template's id
		$('#element-add-form select#type').val(this.template().id());
		// trigger change
		$('#element-add-form select#type').trigger('change');
		// show modal
		$('#editElementPopup').modal({
			backdrop: 'static',
			keyboard: false
		}).draggable("disable");
	};

	Item.prototype.erase = function() {
		if (!this.has("id")) {
			return false;
		}
		$.ajax({
			'url' : globalVariables.baseUrl + '/admin/elements/delete?id=' + this.field("id"),
			'cache' : false,
			'dataType' : 'json',
			'type' : 'GET'
		});
	};

	Item.prototype.refresh = function() {
		var me = this;
		if (this.test("position") && this.test("categorie_id")) {
			return false;
		}
		if (this.parent().has("id")) {
			this.field("categorie_id", this.parent().field("id"));
		} else {
			return false;
		}
		this.manager().invoke($('#editElementPopup form'), function(field, info) {
			if (!me.has(info.native)) {
				return null;
			}
			return me.field(info.native);
		});
		$.post(this.manager().form().attr("action"),
			this.manager().form().serialize()
		);
		return true;
	};

	Item.prototype.read = function(form, id) {
	};

	Item.prototype._renderLabelBefore = function() {
		var label = this.field("label");
		if (label.length > ITEM_LABEL_LIMIT) {
			label = label.substring(0, ITEM_LABEL_LIMIT) + "...";
		}
		return $("<div></div>", {
			html: label,
			style: "float: left;" +
			"border: dotted black 1px;" +
			"border-radius: 5px;" +
			"padding-right: 2px;" +
			"padding-left: 2px;"
		});
	};

	Item.prototype._renderLabelAfter = function() {
		var label = this.field("label_after");
		if (label.length > ITEM_LABEL_LIMIT) {
			label = label.substring(0, ITEM_LABEL_LIMIT) + "...";
		}
		return $("<div></div>", {
			html: label,
			style: "border: dotted black 1px;" +
			"border-radius: 5px;" +
			"padding-right: 2px;" +
			"padding-left: 2px;"
		});
	};

	var _fetchDependencies = function(me) {
		var success = function(data, textStatus, jqXHR) {
			if (data.success != true) {
				return true;
			}
			var data = data.data;
			$('#controlValues option').remove();
			for (var i = 0; i < data.comboValues.length; i++) {
				var option = $('<option>').prop({
					'value': data.comboValues[i].id
				}).text('[ID ' + data.comboValues[i].id + '] ' + data.comboValues[i].value);
				$('#controlValues').append(option);
			}
			// Ставим список всех контролов. Он обновляется всякий раз.
			$('#controlDependencesList option').remove();
			for (var i = 0; i < data.controls.length; i++) {
				var option = $('<option>').prop({
					'value': data.controls[i].id
				}).text(data.controls[i].label);
				$('#controlDependencesList').append(option);
			}
			$('#controlValues').trigger('change');
			// Ставим список действий
			if ($('#controlActions option').length == 0) {
				$('#controlActions option').remove();
				for (var i = 0; i < data.actions.length; i++) {
					var option = $('<option>').prop({
						'value': i
					}).text(data.actions[i]);
					if (i == 0) {
						$(option).prop('selected', true);
					}
					$('#controlActions').append(option);
				}
			}
			// По событию shown - вызов функции, которая спрячет запрещённые для данного элемента направления
			$('#editDependencesPopup').on('shown.bs.modal', function(e) {
				testDirection();
			});
			$('#valuesNotToPrint').val(data.notPrintedValues);
			$('#editDependencesPopup').modal({
				backdrop: 'static',
				keyboard: false
			}).draggable("disable");
		};
		$.ajax({
			'url': globalVariables.baseUrl + '/admin/elements/getdependences?id=' + me.field("id"),
			'cache': false,
			'dataType': 'json',
			'type': 'GET',
			'success': success
		})
	};

	Item.prototype._renderDependenciesButton = function(style) {
		var that = this;
		if (!this.has("id") ||
			this.template().key() != "auto-complete" &&
			this.template().key() != "drop-down" &&
			this.template().key() != "dictionary"
		) {
			return undefined;
		}
		if (!this.has("guide_id") || !this.field("guide_id")) {
			return undefined;
		}
		return $("<span></span>", {
			class: "glyphicon glyphicon-cog",
			style: style || "margin-right: 5px; margin-left: 3px;"
		}).click(function() {
			if (that.has("id")) {
				// Oh, sorry Jesus for that (variable is declared in another file :D Thanks Igor!)
				ElementsApi.currentRow = that.field("id");
				// fix: issue #10354
				$("#dependences").jqGrid('setGridParam', {
					url: window["globalVariables"]["baseUrl"] + '/admin/elements/getDependencesList' + '?id=' + ElementsApi.currentRow,
					datatype: 'json'
				});
				$("#dependences").trigger('reloadGrid');
				// Fetch dependencies
				_fetchDependencies(that);
			}
		});
	};

	Item.prototype.render = function() {
		var that = this;
		var s = $("<div></div>", {
			style: "cursor: default; box-sizing: border-box; float: left;",
			class: "template-engine-item"
		});
		if (this.has("label") && this.field("label").length) {
			s.append(this._renderLabelBefore());
		}
		var title = this.category() && this.category().has("name") ?
			this.category().field("name") : this.template().title();
		s.append(
			$("<div></div>", {
				html: title,
				class: "template-engine-item-title",
				style: "float: left;" +
				"margin-right: 2px;" +
				"margin-left: 2px"
			})
		);
		if (this.has("label_after") && this.field("label_after").length) {
			s.append(this._renderLabelAfter());
		}
		s.append(
			$("<div></div>", {
				style: "float: left; margin-left: 5px;"
			})
		).append(
			that._renderDependenciesButton()
		).append(
			this.template().key() != "category" ?
				that._renderEditButton() : undefined
		).append(
			that._renderRemoveButton("margin-right: 0;")
		);
		if (this.template().key() == "category") {
			s.css("background-color", "lightgray");
		}
		return s;
	};

	Item.prototype.clone = function(parent, model, selector) {
		return new Item(parent, model, selector || null, this.template());
	};

	/**
	 * @param [value] {Item|String|undefined} - Item or
	 *      it's name (for abstract type)
	 * @returns {Item|String} - For getter template element
	 */
	Item.prototype.template = function(value) {
		if (value !== undefined) {
			this._elementTemplate = value;
		}
		return this._elementTemplate;
	};

	var Category = function(parent, model, selector, template) {
		Component.call(this, parent, model, selector,
			"id," +
			"name," +
			"/parent_id," +
			"is_dynamic," +
			"/position," +
			"is_wrapped"
		);
		this._template = template;
		this._reference = null;
	};

	extend(Category, Component);
	extend(Category, Draggable);
	extend(Category, Droppable);

	Category["-instance"] = null;

	var _updateCategory = function(event, data) {
		var json = $.parseJSON(data);
		if (!json.success) {
			throw new Error(json);
		}
		Category["-instance"].model(json["model"], true);
		Category["-instance"].update();
	};

	$(document).ready(function() {
		$("#categorie-add-form").on("success", function(event, data) {
			_updateCategory(event, data);
			ParentCategoryUpdater.afterAppend(Category["-instance"]);
			hasChanges = true;
		});
		$("#categorie-edit-form").on("success", function(event, data) {
			_updateCategory(event, data);
			ParentCategoryUpdater.afterRename(Category["-instance"]);
			hasChanges = true;
		});
	});

	/**
	 *
	 * @param [reference] {Item} - Элемент, ссылающийся на категорию
	 * @returns {Item} - Ссылка на категорию
	 */
	Category.prototype.reference = function(reference) {
		if (arguments.length > 0) {
			this._reference = reference;
		}
		return this._reference;
	};

	Category.prototype.write = function() {
		var me = this;
		if (this.parent().has("id")) {
			if (!(this.parent() instanceof CategoryCollection)) {
				me.field("parent_id", this.parent().field("id"));
			} else {
				me.field("parent_id", -1);
			}
		} else {
			me.field("parent_id", -1);
			//return false;
		}
		this.manager().invoke($('#addCategoriePopup form'),
			function(field, info) {
				if (info.hidden) {
					field.parent(".col-xs-9").parent(".form-group")
						.css("visibility", "hidden")
						.css("position", "absolute");
					return me.field(info.native);
				} else {
					if (me.has(info.native)) {
						return me.field(info.native);
					}
					return null;
				}
			}
		);
		Category["-instance"] = this;
		$('#addCategoriePopup').modal({
			backdrop: 'static',
			keyboard: false
		}).draggable("disable").css("z-index", 1051);
	};

	Category.prototype.edit = function() {
		var me = this;
		this.manager().invoke($('#editCategoriePopup form'), function(field, info) {
			if (info.hidden) {
				field.parent(".col-xs-9").parent(".form-group")
					.css("visibility", "hidden")
					.css("position", "absolute");
			}
			return me.field(info.native);
		});
		Category["-instance"] = this;
		$('#editCategoriePopup').modal({
			backdrop: 'static',
			keyboard: false
		}).draggable("disable").css("z-index", 1051);
	};

	Category.prototype.erase = function() {
		if (!this.has("id")) {
			return false;
		}
		$.ajax({
			'url' : globalVariables.baseUrl + '/admin/categories/delete?id=' + this.field("id"),
			'cache' : false,
			'dataType' : 'json',
			'type' : 'GET'
		});
		ParentCategoryUpdater.afterRemove(this);
	};

	Category.prototype.refresh = function() {
		var me = this;
		if (!this.parent() || this.parent() instanceof CategoryCollection) {
			this.field("parent_id", -1);
		}
		if (this.test("position") && this.test("parent_id")) {
			return false;
		}
		this.manager().invoke($('#editCategoriePopup form'), function(field, info) {
			if (!me.has(info.native)) {
				return null;
			}
			return me.field(info.native);
		});
		$.post(this.manager().form().attr("action"),
			this.manager().form().serialize()
		);
		return true;
	};

	Category.prototype.read = function() {
	};

	Category.prototype.clone = function(parent, model, selector) {
		// TODO You must also clone all category elements
		return new Category(parent, model, selector);
	};

	Category.prototype.template = function() {
		return this._template;
	};

	Category.prototype.defaults = function() {
		return {
		};
	};

	Category.prototype.update = function(state) {
		// render new selector
		var s = this.render(
			this.selector().children(".template-engine-items"),
			this.selector().children(".template-engine-list")
		);// .data("instance", this);
		// replace current selector with new
		this.selector().replaceWith(s);
		// replace instance's selector
		this.selector(s);
	};

	Category.prototype._renderDragButton = function(style) {
		var me = this;
		var b = $("<span></span>", {
			class: "glyphicon glyphicon-link",
			style: style || "margin-right: 5px;"
		});
		b.click(function() {
			if (me.parent() instanceof CategoryCollection) {
				return true;
			}
			if (me.reference()) {
				return true;
			}
			var item = new Item(me.parent(), null, null,
				TemplateEngine.getTemplateCollection().find("category")
			);
			item.category(me);
			me.reference(item);
			me.parent().append(item);
			item.update();
			hasChanges = true;
		});
		return b;
	};

	Category.prototype.render = function(items, categories) {
		var that = this;
		var name = this.has("name") ? this.field("name") : "Категория";
		if (name.length > CATEGORY_STRING_LIMIT) {
			name = name.substring(0, CATEGORY_STRING_LIMIT) + "...";
		}
		var s = $("<li></li>", {
			class: "template-engine-category dd-item"
		}).append(
			$("<div></div>", {
				style: "float: left;"
			}).append(
				$("<div></div>", {
					class: "template-engine-handle-wrapper",
					style: "float: left;"
				}).append(
					$("<div></div>", {
						class: "template-engine-handle dd-handle",
						style: "float: left;",
						html: name
					})
				).append(
					that._renderDragButton()
				).append(
					that._renderEditButton()
				).append(
					that._renderRemoveButton()
				)
			)
		).append(
			items || $("<div></div>", {
				class: "template-engine-items"
			})
		);
		if (categories) {
			s.append(categories);
		}
		if (this.reference()) {
			this.reference().update();
		}
		return s;
	};

	Category.prototype.append = function(element) {
		// fetch container with items
		var items = this.selector().children(".template-engine-items");
		// check for parent to parent append
		if (this === element) {
			return false;
		}
		// if we've appended node to tree
		if (Node.prototype.append.call(this, element)) {
			items.append(element.selector());
		}
		return true;
	};

	Category.prototype.drag = function() {
		this.selector().draggable();
	};

	Category.prototype.drop = function() {
		// this closure
		var that = this;
		// apply sortable
		this.selector().find(".template-engine-items").sortable({
			update: function(e, ui) {
				// get instance
				var item = $(ui.item).data("instance");
				// recompute all children indexes
				that.compute();
				// after update set parent identifier
				if (item.parent().has("id")) {
					item.field("categorie_id", item.parent().field("id"));
				}
				// set has been changed flag to true
				if (item.has("id")) {
					hasChanges = true;
				}
			}
		}).droppable({
			accept: function(helper) {
				// get helper's instance
				var me = helper.data("instance");
				// exit if try to check non-template element, but we can
				// move element from another category so we have to
				// accept it and add extra condition in drop event
				if (!(me instanceof Template)) {
					if (me instanceof Item && me.category()) {
						return me.category().parent() == that;
					}
					return true;
				}
				// check template for category type
				return (
				me.key() !== "category" &&
				me.key() !== "clone" &&
				me.key() !== "static"
				);
			},
			drop: function(e, ui) {
				// get selector
				var selector = ui.helper;
				// get received instance
				var me = selector.data("instance");
				var category = selector.data("category");
				// if we met template, then we gonna
				// create another template's instance
				// else we simply has moved item
				// another category
				if (me instanceof Template) {
					// create new item element (we can't create another one type)
					var item = new Item(
						that, clone(me.model()), null, me, category
					);
					// if template has category type
					if (me.key() == "category") {
						if (category.parent() != that) {
							return false;
						}
						// remove old reference
						if (category.reference()) {
							category.reference().remove();
						}
						// attach category reference to item and
						// item reference to category
						item.category(category);
						category.reference(item);
					}
					// append created item to collection
					that.append(item);
				} else {
					// don't append themselves to
					// it's parents
					if (me.parent() === that) {
						return true;
					}
					// get original element's instance
					me = ui.draggable.data("instance");
					// append instance to new parent (it will
					// be removed from old automatically)
					//me.parent(that);
					Node.prototype.append.call(that, me);
					// detach current selector
					me.selector().detach();
					// render new selector and append to parent's children
					that.selector().children(
						".template-engine-items"
					).append(
						me.selector(
							me.render().data("instance", me)
						)
					);
					// after render set instance's parent identifier
					if (that.has("id")) {
						me.field("categorie_id", that.field("id"));
					}
				}
				hasChanges = true;
			},
			revert: "invalid"
		});
	};

	var CategoryPatcher = {
		construct: function() {
			this._patch = {};
		},
		put: function(key, value) {
			if (this._patch[key]) {
				return false;
			}
			this._patch[key] = value;
			return true;
		},
		map: function() {
			return this._patch;
		},
		get: function(key) {
			return this._patch[key];
		},
		_patch: {}
	};

	var CategoryCollection = function(widget, model, selector) {
		Component.call(this, widget, model, selector);
	};

	extend(CategoryCollection, Component);
	extend(CategoryCollection, Droppable);

	CategoryCollection.prototype.render = function() {
		var dd = $("<div></div>", {
			class: "dd template-engine-nestable dd"
		}).append(
			$("<ol></ol>", {
				class: "template-engine-list"
			})
		);
		return $("<div></div>", {
			class: "template-engine-category-collection"
		}).append(dd);
	};

	CategoryCollection.prototype.update = function() {
		// don't update category collection
	};

	CategoryCollection.prototype.refresh = function() {
		// don't refresh category collection
	};

	CategoryCollection.prototype.read = function() {
		// can't read category collection
	};

	CategoryCollection.prototype.append = function(element) {
		if (this === element) {
			return false;
		}
		if (Node.prototype.append.call(this, element)) {
			this.selector().children(".dd").children(".template-engine-list").append(element.selector());
		}
		return true;
	};

	CategoryCollection.prototype.afterDrop = function(category) {
		// remove draggable option (cuz for sorting we use nestable)
		category.selector().draggable("disable");
		// remove all jquery-ui classes (not needed anymore)
		category.selector()
			.removeClass("ui-draggable")
			.removeClass("ui-draggable-handle")
			.removeClass("ui-draggable-disabled");
		// return self instance
		return this;
	};

	CategoryCollection.prototype.drop = function() {
		var that = this;
		var template = null;
		var finish = function(item, parent) {
			// Display all items
			$(".template-engine-items").css("visibility", "visible");
			// get item and parent instances (to reappend child)
			var itemInstance = $(item).data("instance");
			var parentInstance = parent ? $(parent).data("instance") : that;
			// if we have some parameter null, then skip update
			if (!item || !itemInstance || !parentInstance) {
				return false;
			}
			// remove from item's parent and append to another
			Node.prototype.remove.call(itemInstance.parent(), itemInstance);
			Node.prototype.append.call(parentInstance, itemInstance);
			// if we've moved category and saved node then we have to change
			// it's glyphicon and action for update
			if (parentInstance.length() > 0) {
				if (!(parentInstance instanceof CategoryCollection)) {
					itemInstance.field("parent_id", parentInstance.field("id"));
				} else {
					itemInstance.field("parent_id", -1);
				}
			}
			// if template has category type then remove old reference's selector
			// and append to parent's selector
			if (itemInstance.reference() && itemInstance.reference().parent() != parentInstance) {
				itemInstance.reference().remove();
				if (!(parentInstance instanceof CategoryCollection)) {
					parentInstance.append(itemInstance.reference());
				} else {
					itemInstance.reference().parent(parentInstance);
				}
			}
			// set has been changed flag to true
			if (itemInstance.has("id")) {
				hasChanges = true;
			}
			// reset native parent
			itemInstance.native("parent_id", 0);
			// update all positions
			parentInstance.compute();
		};
		this.selector().droppable({
			accept: function(helper) {
				var me = helper.data("instance");
				if (!(me instanceof Template)) {
					return false;
				}
				return (
				me.key() === "category" ||
				me.key() === "clone"
				);
			},
			drop: function(e, ui) {
				var templateInstance = ui.helper.data("instance");
				if (!(templateInstance instanceof Template)) {
					return false;
				}
				template = templateInstance;
				if (templateInstance.key() === "category") {
					var category = new Category(
						that, clone(templateInstance.model())
					);
					that.afterDrop(category);
					that.append(category);
				} else {
					$("#findCategoryPopup").modal({
						backdrop: 'static',
						keyboard: false
					}).draggable("disable");
				}
				// Move scroll to bottom of container
				that.selector().scrollTop(
					that.selector()[0].scrollHeight
				);
				hasChanges = true;
			}
		}).find(".dd").nestable({
			listClass: "template-engine-list",
			itemClass: "template-engine-category",
			handleClass: "template-engine-handle",
			rootClass: "template-engine-nestable",
			placeClass: "template-engine-placeholder",
			expandBtnHTML: "",
			collapseBtnHTML: "",
			maxDepth: 500,
			finish: finish,
			begin: function() {
				$(".template-engine-items").css("visibility", "hidden");
			}
		});
	};

	CategoryCollection.prototype.afterRegister = function() {
		for (var key in CategoryPatcher.map()) {
			var item = CategoryPatcher.get(key);
			var c = TemplateEngine.getCategoryCollection().findByPath(key);
			if (!c || !(c instanceof Category)) {
				item.remove();
				continue;
			}
			c.reference(item);
			item.category(c);
			item.update();
		}
		CategoryPatcher.construct();
	};

	CategoryCollection.prototype.register = function(model, clone, template) {
		var collection = this;
		var elements = model["elements"];
		var children = model["children"];
		if (clone === true) {
			model["id"] = undefined;
		}
		model["elements"] = undefined;
		model["children"] = undefined;
		if (!model["name"]) {
			return false;
		}
		var c = new Category(null, model, null, template);
		var offset = 0;
		for (var i in elements) {
			if (clone === true) {
				elements[i]["id"] = undefined;
			}
			var item = new Item(c, elements[i], null,
				_getTemplateByID(elements[i]["type"])
			);
			item.model(elements[i], true);
			while (+i + 1 + offset < +item.field("position")) {
				var path = item.field("path").split('.');
				if (!+i) {
					++offset;
					continue;
				}
				if (path.length > 1) {
					var prevPath = "";
					for (var j in path) {
						if (j == path.length - 1) {
							prevPath += (+path[j] - (+item.field("position") - (+i + offset + 1)));
						} else {
							prevPath += path[j] + ".";
						}
					}
					if (!CategoryPatcher.get(prevPath)) {
						var ref = new Item(c, null, null,
							TemplateEngine.getTemplateCollection().find("category")
						);
						CategoryPatcher.put(prevPath, ref);
						c.append(ref);
					}
					++offset;
				}
			}
			c.append(item);
		}
		if (!(collection instanceof CategoryCollection)) {
			CategoryCollection.prototype.afterDrop.call(
				collection, c
			).append(c);
			c.selector().detach().appendTo(
				collection.selector().children(".template-engine-list")
			);
		} else {
			collection.afterDrop(c).append(c);
		}
		if (children) {
			if (!c.selector().children(".template-engine-list").length) {
				c.selector().append(
					$("<ol></ol>", {
						class: "template-engine-list dd-list"
					})
				);
			}
			for (i in children) {
				if (+i + 1 != +children[i]["position"] && !CategoryPatcher.get(children[i]["path"])) {
					ref = new Item(c, null, null, TemplateEngine.getTemplateCollection().find("category"));
					c.append(ref);
					CategoryPatcher.put(children[i]["path"], ref);
				}
				CategoryCollection.prototype.register.call(
					c, children[i], clone
				);
			}
		}
		return c;
	};

	var Widget = function(widgetSelector, templateCollection) {
		// invoke component constructor
		Component.call(this, null, null, widgetSelector);
		// initialize collections
		this._templateCollection = templateCollection;
		this._categoryCollection = new CategoryCollection(this);
		// append collections to widget
		this.append(this._categoryCollection);
		this.append(this._templateCollection);
	};

	extend(Widget, Component);

	Widget.prototype.render = function() {
		return this.selector();
	};

	Widget.prototype.getTemplateCollection = function() {
		return this._templateCollection;
	};

	Widget.prototype.getCategoryCollection = function() {
		return this._categoryCollection;
	};

	var WidgetCollection = {
		register: function(selector) {
			if (this._widgetList.length > 1) {
				assert("You can't register more then one TemplateEngine widgets");
			}
			this._widgetList.push(
				new Widget(selector, this._templateCollection)
			);
		},
		widget: function() {
			return this._widgetList[0];
		},
		restart: function() {
			var i;
			for (i in this._widgetList) {
				var w = this._widgetList[i];
				if (!w.getCategoryCollection()) {
					continue;
				}
				var children = w.getCategoryCollection().children();
				for (i in children) {
					if (!children[i]) {
						continue;
					}
					children[i].remove();
				}
				w.getCategoryCollection().selector()
					.find(".template-engine-list").empty();
			}
		},
		_templateCollection: new TemplateCollection(),
		_widgetList: []
	};

	$(document).ready(function() {
		$(".template-engine-widget").each(function(i, w) {
			WidgetCollection.register($(w));
		});
		/* $(document).contextmenu(function() {
			return false;
		}); */
	});

	var collection = WidgetCollection._templateCollection;

	// register basic templates
	collection.append(new Template(collection, "category",      "Категория",           -3));
	collection.append(new Template(collection, "clone",         "Клонировать",         -2));
	collection.append(new Template(collection, "text",          "Текстовое поле",       0));
	collection.append(new Template(collection, "text-area",     "Текстовая область",    1));
	collection.append(new Template(collection, "number",        "Числовое поле",        5));
	collection.append(new Template(collection, "drop-down",     "Выпадающий список",    2));
	collection.append(new Template(collection, "auto-complete", "Множественный список", 3));
	collection.append(new Template(collection, "table",         "Таблица",              4));
	collection.append(new Template(collection, "dictionary",    "Двухколонный список",  7));
	collection.append(new Template(collection, "date",          "Дата",                 6));
	// register extra templates
	//collection.append(new Template(collection, "token", ","));
	//collection.append(new Template(collection, "token", "."));
	//collection.append(new Template(collection, "token", "-"));
	//collection.append(new Template(collection, "token", ":"));
	//collection.append(new Template(collection, "token", ";"));

	// highlight static and dynamic categories in template view

	try {
		collection.find("category").selector()
			.addClass("template-engine-coral");
	} catch (ignore) {
	}

	try {
		collection.find("clone").selector()
			.addClass("template-engine-category-static");
	} catch (ignore) {
	}

	try {
		collection.find("static").selector()
			.addClass("template-engine-category-static");
	} catch (ignore) {
	}

	var ParentCategoryUpdater = {
		afterRename: function(category) {
			if (!category.has("id")) {
				return false;
			}
			$(document.body).find("#parentId, #categorieId").children().each(function(i, e) {
				if ($(e).val() == category.field("id")) {
					$(e).html(category.field("name"));
				}
			});
		},
		afterAppend: function(category) {
			if (!category.has("id")) {
				return false;
			}
			$(document.body).find("#parentId, #categorieId").each(function(i, e) {
				$(e).append(
					$("<option></option>", {
						value: category.field("id"),
						html: category.field("name")
					})
				);
			});
		},
		afterRemove: function(category) {
			if (!category.has("id")) {
				return false;
			}
			$(document.body).find("#parentId, #categorieId").each(function(i, e) {
				$(e).children("option[value=\"" + category.field("id") + "\"]").remove();
			});
		}
	};

	var _getTemplateByID = function(id) {
		// get template collection's children
		var children = collection.children();
		// look though all templates in collection
		// and find template with necessary identifier
		for (var i in children) {
			if (children[i].id() === parseInt(id)) {
				return children[i];
			}
		}
		// throw an exception, if we can't find
		// template by identifier
		assert("TemplateEngine/getTemplateByID(): \"Unresolved template id (" + id + ")\"");
	};

	TemplateEngine.registerTemplate = function(model) {
		// restart current collection
		TemplateEngine.restart();
		// append model to collection
		var collection = WidgetCollection.widget().getCategoryCollection();
		collection.model(model, true);
		// initialize collection with template model collection
		CategoryPatcher.construct();
		for (var i in model.categories) {
			var isContains = false;
			for (var j in collection.children()) {
				if (!collection.children()[j]) {
					continue;
				}
				try {
					var jid = +collection.children()[j].field("id");
				} catch (e) {
					continue;
				}
				if (jid == +model.categories[i]["id"]) {
					isContains = true;
					break;
				}
			}
			if (isContains) {
				continue;
			}
			WidgetCollection.widget().getCategoryCollection().register(
				model.categories[i]
			);
		}
		WidgetCollection.widget().getCategoryCollection().afterRegister();
		// return self
		return TemplateEngine;
	};

	TemplateEngine.isCategory = function(item) {
		return item instanceof Category;
	};

	TemplateEngine.isCollection = function(item) {
		return item instanceof CategoryCollection;
	};

	TemplateEngine.isItem = function(item) {
		return item instanceof Item;
	};

	TemplateEngine.restart = function() {
		// reset widget collection (it will
		// remove all categories with elements)
		WidgetCollection.restart();
		// reset has been changed flag
		hasChanges = false;
	};

	TemplateEngine.getTemplateCollection = function() {
		return WidgetCollection.widget().getTemplateCollection();
	};

	TemplateEngine.getCategoryCollection = function() {
		return WidgetCollection.widget().getCategoryCollection();
	};

	var hasChanges = false;

	var saveTemplate = function(strict, after) {
		hasChanges = false;
		var cc = TemplateEngine.getCategoryCollection();
		cc.compute(true);
		var hasNotSaved = false;
		var result = [];
		var update = function(item) {
			if (!item.has("id")) {
				if (!(item.template() && item.template().key() == "category")) {
					hasNotSaved = true;
				}
				return false;
			}
			for (var i in item.children()) {
				if (!item.children(i)) {
					continue;
				}
				update(item.children(i));
			}
			if (item instanceof CategoryCollection) {
				return true;
			}
			if (item instanceof Item && !item.category()) {
				result.push({
					type: "element",
					id: item.field("id"),
					position: item.field("position"),
					category: item.parent().field("id")
				});
			} else {
				result.push({
					type: "category",
					id: item.field("id"),
					position: item.field("position"),
					category: item.field("parent_id")
				});
			}
			return true;
		};
		update(cc);
		var json = "[";
		for (var i in cc.children()) {
			if (!cc.children(i) || !cc.children(i).has("id")) {
				continue;
			}
			json += cc.children(i).field("id") + ",";
		}
		if (json.length > 1) {
			json = json.substring(0, json.length - 1);
		}
		json += "]";
		if (!strict && hasNotSaved && !confirm('Часть элементов имеют незаполненные данные, при сохранении данные элементы будут потреряны. Продолжить? ')) {
			hasChanges = true;
			return false;
		}
		$(".saving-template").css("visibility", "visible");
		// set request on server to update template categories
		$.post(globalVariables.baseUrl + "/admin/templates/utc", {
			tid: cc.field("id"),
			categories: JSON.stringify(result),
			cids: json
		}, function(data) {
			if (!after) {
				$("#designTemplatePopup").modal("hide");
			} else {
				after();
			}
			$(".saving-template").css("visibility", "hidden");
		});
	};

	$(document).ready(function() {

		$("#designTemplatePopup").find(".btn-primary").click(function() {
			saveTemplate();
		});

		$("#designTemplatePopup").on("hide.bs.modal", function(e) {
			if (hasChanges && !confirm('В шаблон были внесены изменения, которые не были сохранены. При закрытии они будут утеряны. Закрыть?')) {
				e.preventDefault();
				return false;
			}
		});

		$("#findCategoryPopup form .btn-warning").click(function() {
			var value = $("#findCategoryPopup form #parentId").val();
			if (value < 0) {
				return true;
			}
			if (!confirm("Категория будет перемещена. Все изменения сделанные и сохранённые, в дизайнере шаблонов, после переноса, будут также отображаться в перенесённой категории. Вы уверены?")) {
				return true;
			}
			$("#findCategoryPopup .saving-template").css("visibility", "visible");
			$.ajax({
				'url': globalVariables.baseUrl + '/admin/categories/move?id=' + value,
				'cache': false,
				'dataType': 'json',
				'type': 'GET'
			}).done(function (data) {
				if (!data.success) {
					console.log(data);
					return true;
				}
				var findAndDetach = function (item) {
					if (!item.has("id")) {
						return false;
					}
					if (+item.field("id") == value) {
						item.remove();
						return true;
					}
					for (var i in item.children()) {
						if (!item.children(i)) {
							continue;
						}
						if (findAndDetach(item.children(i))) {
							return true;
						}
					}
					return false;
				};
				var cc = TemplateEngine.getCategoryCollection();
				findAndDetach(cc);
				CategoryPatcher.construct();
				cc.register(data["model"], false);
				WidgetCollection.widget().getCategoryCollection().afterRegister();
				hasChanges = true;
				$("#findCategoryPopup .saving-template").css("visibility", "hidden");
				$("#findCategoryPopup").modal("hide");
			});
		});

		$("#numberStep").change(function() {
			if (!+$(this).val()) {
				$(this).val(1);
			}
		});

		$("#findCategoryPopup form .btn-success").click(function() {
			var value = $("#findCategoryPopup form #parentId").val();
			if (value < 0) {
				return true;
			}
			$("#findCategoryPopup .saving-template").css("visibility", "visible");
			$.ajax({
				'url': globalVariables.baseUrl + '/admin/categories/clone?id=' + value,
				'cache': false,
				'dataType': 'json',
				'type': 'GET'
			}).done(function(data) {
				CategoryPatcher.construct();
				var c = TemplateEngine.getCategoryCollection()
					.register(data["model"], false);
				var appendToList = function(c) {
					ParentCategoryUpdater.afterAppend(c);
					for (var i in c.children()) {
						if (c.children(i) && c.children(i) instanceof Category) {
							appendToList(c.children(i));
						}
					}
				};
				hasChanges = true;
				appendToList(c);
				WidgetCollection.widget().getCategoryCollection().afterRegister();
				$("#findCategoryPopup .saving-template").css("visibility", "hidden");
				$("#findCategoryPopup").modal("hide");
			});
		});
	});

})(TemplateEngine);