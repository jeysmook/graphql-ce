<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Integration\Model\Oauth\Token\RequestThrottler;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$customerEmail = 'customer@example.com';

/** @var \Magento\Framework\Registry $registry */
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/**
 * @var Magento\Customer\Api\CustomerRepositoryInterface
 */
$customerRepository = $objectManager->create(\Magento\Customer\Api\CustomerRepositoryInterface::class);
try {
    $customer = $customerRepository->get($customerEmail);
    $customerRepository->delete($customer);
} catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
    // not found
}

/* Unlock account if it was locked for tokens retrieval */
/** @var RequestThrottler $throttler */
$throttler = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(RequestThrottler::class);
$throttler->resetAuthenticationFailuresCount($customerEmail, RequestThrottler::USER_TYPE_CUSTOMER);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
