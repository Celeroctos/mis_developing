<?php
class QuickPanelListWidget extends CWidget {
    public function run() {
        $this->render('application.widgets.views.QuickPanelListWidget', array(
            'icons' => QuickPanelIcon::model()->getRows()
        ));
    }
}

?>