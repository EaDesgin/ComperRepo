<?php

/**
 * Magento Composer Repository Manager
 *
 * @package Genmato_ComposerRepo
 * @author  Vladimir Kerkhoff <v.kerkhoff@genmato.com>
 * @created 2015-12-09
 * @copyright Copyright (c) 2015 Genmato BV, https://genmato.com.
 */

class Genmato_ComposerRepo_Block_Adminhtml_Composer_Packages extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_headerText = Mage::helper('genmato_composerrepo')->__('Composer Packages');
        $this->_blockGroup = 'genmato_composerrepo';
        $this->_controller = 'adminhtml_composer_packages';

    }
}