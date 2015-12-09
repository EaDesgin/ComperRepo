<?php

class Genmato_ComposerRepo_Helper_Packages extends Genmato_ComposerRepo_Helper_Data
{
    public function getPackagesJson($customerId)
    {
        $config = [];
        $config['notify-batch'] = Mage::getUrl('composer/download/notify');

        $cache = Mage::app()->getCache();
        $cacheTags = [];
        $cacheTags[] = Genmato_ComposerRepo_Model_Packages::CACHE_TAG;
        $cacheTags[] = Genmato_ComposerRepo_Model_Customer_Auth::CACHE_TAG . $customerId;

        $packages = false;
        if (Mage::app()->useCache('packages_json')) {
            $packages = json_decode($cache->load(Genmato_ComposerRepo_Model_Customer_Auth::CACHE_TAG . $customerId), true);
        }
        if (!$packages) {
            $config['cached'] = false;
            $joinTable = Mage::getSingleton('core/resource')->getTableName('genmato_composerrepo/customer_packages');
            $collection = Mage::getResourceModel('genmato_composerrepo/packages_collection');
            $collection->getSelect()
                ->join(
                    array('customer_packages'=> $joinTable),
                    'main_table.entity_id = customer_packages.package_id',
                    array('max_version'=>'last_allowed_version')
                );
            $collection
                ->addFieldToFilter('main_table.status', array('eq' => Genmato_ComposerRepo_Model_Packages::STATUS_ENABLED))
                ->addFieldToFilter('customer_packages.status', array('eq' => 1));

            foreach ($collection as $package) {
                $cacheTags[] = Genmato_ComposerRepo_Model_Packages::CACHE_KEY.$package->getId();

                $packageData = json_decode($package->getPackageJson(), true);
                foreach ($packageData as $version=>$data) {

                    if (!$package->getMaxVersion() ||
                        $data['version_normalized'] == '9999999-dev' ||
                        version_compare($data['version_normalized'], $package->getMaxVersion(), '<=')) {
                        $packages[$package->getName()][$version] = $data;
                    }
                }
            }
            $collection = Mage::getResourceModel('genmato_composerrepo/packages_collection')
                ->addFieldToFilter(
                    'main_table.status',
                    array(
                        'eq' => Genmato_ComposerRepo_Model_Packages::STATUS_FREE
                    )
                );
            foreach ($collection as $package) {
                $cacheTags[] = Genmato_ComposerRepo_Model_Packages::CACHE_KEY.$package->getId();
                $packages[$package->getName()] = json_decode($package->getPackageJson(), true);
            }

            $cache->save(
                json_encode($packages),
                Genmato_ComposerRepo_Model_Customer_Auth::CACHE_TAG . $customerId,
                $cacheTags
            );
        } else {
            $config['cached'] = true;
        }
        $config['packages'] = $packages;
        return $config;
    }
}