<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\AlternateUrlsPrismic\Type;

use Elgentos\AlternateUrls\Model\AlternateUrl;
use Elgentos\AlternateUrls\Type\AbstractType;
use Elgentos\AlternateUrls\Type\TypeInterface;
use Elgentos\PrismicIO\Block\DocumentResolverTrait;
use Elgentos\PrismicIO\Block\LinkResolverTrait;
use Elgentos\PrismicIO\ViewModel\DocumentResolver;
use Elgentos\PrismicIO\ViewModel\LinkResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class PrismicPage extends AbstractType implements TypeInterface, ArgumentInterface
{
    use DocumentResolverTrait;
    use LinkResolverTrait;

    public function __construct(
        Json $serializer,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        DocumentResolver $documentResolver,
        LinkResolver $linkResolver
    ) {
        parent::__construct(
            $serializer,
            $scopeConfig,
            $storeManager,
            $request
        );

        $this->documentResolver = $documentResolver;
        $this->linkResolver     = $linkResolver;
    }

    public function getAlternateUrls(): array
    {
        $context = $this->getDocumentResolver()
            ->getContext('*');

        if (!$context) {
            return [];
        }

        return array_reduce(
            $this->getMapping(),
            function (array $carry, array $item) use ($context) {
                $link        = clone $context;
                $link->store = $this->storeManager->getStore($item['store_id']);

                try {
                    $carry[] = new AlternateUrl(
                        $item['hreflang'],
                        $this->getLinkResolver()->resolve($link)
                    );
                } catch (NoSuchEntityException $e) {
                    return $carry;
                }

                return $carry;
            },
            []
        );
    }
}
