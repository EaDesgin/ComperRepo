<?php

/**
 * Magento Composer Repository Manager
 *
 * @package Genmato_ComposerRepo
 * @author  Vladimir Kerkhoff <v.kerkhoff@genmato.com>
 * @created 2015-12-09
 * @copyright Copyright (c) 2015 Genmato BV, https://genmato.com.
 */

class Genmato_ComposerRepo_Model_Customer_Packages extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('genmato_composerrepo/customer_packages');
    }

}