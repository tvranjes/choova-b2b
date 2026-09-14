<?php
/**
 * Entry point for the "automotive_hr" store view (path-based: /automotive/hr/).
 */
use Magento\Framework\App\Bootstrap;

require __DIR__ . '/../../../app/bootstrap.php';

$params = $_SERVER;
$params[\Magento\Store\Model\StoreManager::PARAM_RUN_CODE] = 'automotive_hr';
$params[\Magento\Store\Model\StoreManager::PARAM_RUN_TYPE] = 'store';

$bootstrap = Bootstrap::create(BP, $params);
$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
$bootstrap->run($app);
