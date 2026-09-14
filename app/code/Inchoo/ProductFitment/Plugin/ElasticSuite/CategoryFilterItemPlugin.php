<?php

declare(strict_types=1);

namespace Inchoo\ProductFitment\Plugin\ElasticSuite;

use InchooDev\ProductFitment\Model\Fitment\Request as FitmentRequest;
use InchooDev\ProductFitment\Model\Fitment\Url as FitmentUrl;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Smile\ElasticsuiteCatalog\Model\Layer\Filter\Item\Category;

class CategoryFilterItemPlugin
{
    /**
     * @param FitmentRequest $fitmentRequest
     * @param FitmentUrl $fitmentUrl
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        private readonly FitmentRequest $fitmentRequest, // phpcs:ignore
        private readonly FitmentUrl $fitmentUrl, // phpcs:ignore
        private readonly UrlHelper $urlHelper
    ) {
    }

    /**
     * Append fitment query parameters to category filter URLs.
     *
     * @param Category $subject
     * @param string $result
     * @return string
     */
    public function afterGetUrl(Category $subject, string $result): string
    {
        $optionIds = $this->fitmentRequest->getOptionIds();
        if (empty($optionIds)) {
            return $result;
        }

        $params = [];
        foreach ($optionIds as $attributeCode => $optionId) {
            $urlKey = $this->fitmentUrl->getUrlKey($optionId);
            if ($urlKey) {
                $params[$attributeCode] = $urlKey;
            }
        }

        if (empty($params)) {
            return $result;
        }

        return $this->urlHelper->addRequestParam($result, $params);
    }
}
