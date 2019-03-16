<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Integration\Model\Oauth\Token\RequestThrottler;

/** @var \Magento\Framework\Registry $registry */
$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var $customer \Magento\Customer\Model\Customer*/
$customer1 = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer1->load(1);
if ($customer1->getId()) {
    $customer1->delete();
}

$customer2 = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer2->load(2);
if ($customer2->getId()) {
    $customer2->delete();
}

$customer3 = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Customer\Model\Customer::class
);
$customer3->load(3);
if ($customer3->getId()) {
    $customer3->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);

/* Unlock account if it was locked for tokens retrieval */
/** @var RequestThrottler $throttler */
$throttler = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(RequestThrottler::class);
$throttler->resetAuthenticationFailuresCount('customer@search.example.com', RequestThrottler::USER_TYPE_CUSTOMER);
$throttler->resetAuthenticationFailuresCount('customer2@search.example.com', RequestThrottler::USER_TYPE_CUSTOMER);
$throttler->resetAuthenticationFailuresCount('customer3@search.example.com', RequestThrottler::USER_TYPE_CUSTOMER);
