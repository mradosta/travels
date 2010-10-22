<?php

class AppModel extends Model {


	var $actsAs = array('containable');


	/** Overwrite constructor to do "mostly" translations */
    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $this->_initialitation();
    }


	/** To be [optionally] implemented on child classes */
    protected function _initialitation() {
    }

}