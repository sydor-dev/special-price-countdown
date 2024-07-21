<?php

namespace Sydor\SpecialPriceCountdown\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SpecialPriceCountdown extends Template
{
    protected $productRepository;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function getSpecialPriceCountdown($productId)
    {
        $product = $this->productRepository->getById($productId);
        $specialPriceFromDate = $product->getSpecialFromDate();
        $specialPriceToDate = $product->getSpecialToDate();

        if ($specialPriceFromDate && $specialPriceToDate) {
            $fromDate = new \DateTime($specialPriceFromDate);
            $toDate = new \DateTime($specialPriceToDate);
            $interval = $fromDate->diff($toDate);
            return $interval->format('%a days');
        }

        return null;
    }

    public function getSpecialPriceDate($productId, $format = true)
    {
        $product = $this->productRepository->getById($productId);

        $specialPriceDate = $product->getSpecialToDate();

        if (!$specialPriceDate) {
            return null;
        }

        return  $format ? date('Y/m/d H:i:s', strtotime($specialPriceDate)): $specialPriceDate;
    }
}
