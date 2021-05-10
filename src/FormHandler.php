<?php

namespace Bera\Smtp;

class FormHandler 
{
    /**
     * @var AdminMenu $_admin_menu
     */
    private $_admin_menu;

    public function __construct( $admin_menu ) {
        $this->_admin_menu = $admin_menu;
    }

    /**
     * Init hooks
     */
    public function init() {

    }
}