<?php

class Modal extends Widget {

    /**
     * @var string - Modal window's title
     */
    public $title = "";

    /**
     * @var string - Identification number, shall ends on '-modal'
     */
    public $id = null;

    /**
     * @var string|Widget - Content to render, for widget 'call' method
     *  will be invoked
     * @see Widget::call
     */
    public $body = null;

    /**
     * @var array - Array with buttons to put into footer panel, where
     *  array key is button's identification number and body is:
     * + align - align button to some side (supports only 'left' or default 'right')
     * + attributes - array with button html attributes
     * + type - button type
     * + class - button classes
     * + text - displayable text
     */
    public $buttons = [];

    /**
     * @var string - Modal dialog extra classes, like 'modal-lg' or 'modal-sm'
     */
    public $class = null;

    /**
     * @var bool - Set it to false to disable fade effect
     */
    public $fade = true;

    /**
     * @var string - Custom header content
     */
    public $header = null;

    /**
     * @var array - Options to disable
     */
    public $enable = [
        "header-close-button" => true,
        "footer-close-button" => true
    ];

    /**
     * @var string - Text on close button
     */
    public $closeText = "Закрыть";

    /**
     * @var string - Class for close button
     */
    public $closeClass = "btn btn-default";

	/**
	 * @var bool - Should modal be draggable?
	 */
	public $draggable = false;

    /**
     * Initialize widget
     */
    public function init() {
        if ($this->body instanceof Widget) {
            $this->body = $this->body->call(true);
        }
        foreach ($this->buttons as $i => &$button) {
            if (!isset($button["attributes"])) {
                $button["attributes"] = [];
            }
            if (isset($button["type"])) {
                $button["attributes"]["type"] = $button["type"];
            } else {
                $button["attributes"]["type"] = "button";
            }
            if (!isset($button["text"])) {
                $button["text"] = "";
            }
            if (!isset($button["align"])) {
                $button["align"] = "right";
            }
            if (isset($button["class"])) {
                $button["attributes"]["class"] = $button["class"];
            }
            $button["attributes"]["id"] = $i;
        }
        if (!$this->id) {
            $this->id = UniqueGenerator::generate("modal");
        }
        $this->renderModal();
    }

    /**
     * Run widget
     * @throws CException
     */
    public function run() {
        print CHtml::closeTag("div");
        print CHtml::closeTag("div");
        $this->renderFooter();
        print CHtml::closeTag("div");
        print CHtml::closeTag("div");
        print CHtml::closeTag("div");
    }

    /**
     * Render modal window
     */
    public function renderModal() {
        print CHtml::openTag("div", [
            "class" => "modal" . ($this->fade ? " fade" : ""),
            "id" => $this->id,
			"data-draggable" => $this->draggable ? "true" : "false"
        ]);
        print CHtml::openTag("div", [
            "class" => "modal-dialog" . ($this->class ? " " . $this->class : "")
        ]);
        print CHtml::openTag("div", [
            "class" => "modal-content"
        ]);
        $this->renderHeader();
        $this->renderBody();
    }

    /**
     * Render header
     */
    public function renderHeader() {
        print CHtml::openTag("div", [
            "class" => "modal-header"
        ]);
        if ($this->enable["header-close-button"] == true) {
            print CHtml::tag("button", [
                "type" => "button",
                "data-dismiss" => "modal",
                "class" => "close",
                "aria-label" => "Закрыть"
            ], CHtml::tag("span", [
                "aria-hidden" => "true"
            ], "&times;"));
        }
        print CHtml::tag("h4", [
            "class" => "modal-title"
        ], $this->title);
        print CHtml::closeTag("div");
    }

    /**
     * Render body
     */
    public function renderBody() {
        print CHtml::openTag("div", [
            "class" => "modal-body"
        ]);
        print CHtml::openTag("div", [
            "class" => "row"
        ]);
        if ($this->body != null) {
            print $this->body;
        }
    }

    /**
     * Render footer
     */
    public function renderFooter() {
        print CHtml::openTag("div", [
            "class" => "modal-footer"
        ]);
        print CHtml::openTag("table", [
            "width" => "100%"
        ]);
        print CHtml::openTag("tr");
        print CHtml::openTag("td", [
            "align" => "left"
        ]);
        foreach ($this->buttons as $button) {
            if ($button["align"] == "left") {
                print CHtml::tag("button", $button["attributes"], $button["text"]);
            }
        }
        print CHtml::closeTag("td");
		print CHtml::openTag("td", [
			"align" => "middle"
		]);
		foreach ($this->buttons as $button) {
			if ($button["align"] == "center") {
				print CHtml::tag("button", $button["attributes"], $button["text"]);
			}
		}
		print CHtml::closeTag("td");
        print CHtml::openTag("td", [
            "align" => "right"
        ]);
        if ($this->enable["footer-close-button"] == true) {
            print CHtml::tag("button", [
                "class" => $this->closeClass,
                "type" => "button",
                "data-dismiss" => "modal"
            ], $this->closeText);
        }
        foreach ($this->buttons as $button) {
            if ($button["align"] == "right") {
                print CHtml::tag("button", $button["attributes"], $button["text"]);
            }
        }
        print CHtml::closeTag("td");
        print CHtml::closeTag("tr");
        print CHtml::closeTag("table");
        print CHtml::closeTag("div");
    }
}