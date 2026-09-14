<?php
/**
 * Entry point for the "automotive" website (path-based multi-website: /automotive/).
 */
use Magento\Framework\App\Bootstrap;

require __DIR__ . '/../../app/bootstrap.php';

$params = $_SERVER;
$params[\Magento\Store\Model\StoreManager::PARAM_RUN_CODE] = 'automotive';
$params[\Magento\Store\Model\StoreManager::PARAM_RUN_TYPE] = 'website';

$bootstrap = Bootstrap::create(BP, $params);
$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
$bootstrap->run($app);
