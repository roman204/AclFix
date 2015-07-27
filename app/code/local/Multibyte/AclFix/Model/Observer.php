<?php

/**
 * Created by PhpStorm.
 * User: rhutterer
 * Date: 14.07.15
 * Time: 13:00
 */
class Multibyte_AclFix_Model_Observer extends Mage_Core_Model_Abstract {

    public function adminhtmlControllerActionPredispatchStart() {

        $controllerName = Mage::app()->getRequest()->getControllerName();
        $actionName = Mage::app()->getRequest()->getActionName();
        $adminSession = Mage::getSingleton( 'admin/session' );
        $acl = $adminSession->getAcl();
        if (!$acl) {
            return;
        }

        $resourceName = $controllerName . "/" . $actionName;

        if (!preg_match( '/^admin/', $resourceName )) {
            $resourceName = 'admin/' . $resourceName;
        }

        if (!$acl->has( $resourceName )) {
            if (!$adminSession->isAllowed( $resourceName )) {
                $role = $adminSession->getUser()->getRole();
                $acl->allow($role->getRoleType() . $role->getRoleId(), 'admin');
            }
        }


    }

}
