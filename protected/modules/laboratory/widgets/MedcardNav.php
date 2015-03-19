<?php

class MedcardNav extends Widget {

    public function run() {

        $module = $this->controller->getModule() != null ? strtolower($this->controller->getModule()->getId()) : null;
        $action = $this->controller->getAction() != null ? strtolower($this->controller->getAction()->getId()) : $this->controller->defaultAction;

        $this->render(__CLASS__, [
            "list" => [
                "Поиск " => [
                    "module" => "laboratory",
                    "controller" => "medcard",
                    "action" => "view"
                ]
            ],
            "module" => $module,
            "action" => $action,
            "controller" => strtolower($this->controller->getId())
        ]);
    }
} 