<?php

class LayoutMailView extends xView {

    function init() {
        $this->add_data(array(
            'close' => _('Yours sincerly,'),
            'signature' => _('Your Okikoo Team')
        ));
    }

    function unhtmlentities($html) {
        $translation = array_flip(get_html_translation_table(HTML_ENTITIES));
        return strtr($html, $translation);
    }

    function render() {
        $this->data['contents'] = $this->unhtmlentities(@$this->data['contents']);
        return $this->apply('mail.tpl');
    }
}

?>