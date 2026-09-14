<?php

declare(strict_types=1);

namespace Inchoo\ProductFitment\Plugin\ViewModel;

use InchooDev\ProductFitment\Model\Fitment\Request as FitmentRequest;
use InchooDev\ProductFitment\Model\ResourceModel\Fitment as FitmentResource;
use InchooDev\ProductFitment\ViewModel\FitmentSelector;

class FitmentSelectorPlugin
{
    /**
     * @param FitmentRequest $fitmentRequest
     * @param FitmentResource $fitmentResource
     */
    public function __construct(
        private readonly FitmentRequest $fitmentRequest, // phpcs:ignore
        private readonly FitmentResource $fitmentResource
    ) {
    }

    /**
     *  Load all fitments on product listing pages when no specific fitment IDs
     *  are provided (bypasses the layered navigation dependency).
     *
     * @param FitmentSelector $subject
     * @param callable $proceed
     * @param array $fitmentIds
     * @return array
     */
    public function aroundGetDataByIds(
        FitmentSelector $subject,
        callable $proceed,
        array $fitmentIds = []
    ): array {
        if (empty($fitmentIds) && $this->fitmentRequest->isProductListing()) {
            return $subject->prepareData($this->fitmentResource->loadData([]));
        }

        return $proceed($fitmentIds);
    }

    /**
     * @param FitmentSelector $subject
     * @param array $result
     * @return array
     */
    public function afterGetDataByIds(FitmentSelector $subject, array $result): array // phpcs:ignore
    {
        if (empty($result['fitmentOptionAttributes'])) {
            return $result;
        }

        foreach ($result['fitmentOptionAttributes'] as &$attribute) {
            if (!empty($attribute['options'])) {
                $attribute['options'] = $this->natsortByKey($attribute['options'], 'label');
            }
        }
        unset($attribute);

        return $result;
    }

    /**
     * @param array $array
     * @param string $sortByKey
     * @return array
     */
    private function natsortByKey(array $array, string $sortByKey): array
    {
        if (empty($array)) {
            return $array;
        }

        $namedArray = [];
        $naturalSortKeys = [];
        foreach ($array as $item) {
            $naturalSortKeys[] = (string)$item[$sortByKey];
            $namedArray[(string)$item[$sortByKey]] = $item;
        }

        natsort($naturalSortKeys);

        $naturalSortArray = [];
        $sortOrder = 0;
        foreach ($naturalSortKeys as $key) {
            $naturalSortArray[$sortOrder++] = $namedArray[(string)$key];
        }

        return $naturalSortArray;
    }
}
