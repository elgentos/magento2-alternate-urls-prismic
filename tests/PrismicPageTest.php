<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\AlternateUrlsPrismic\Tests\Type;

use Elgentos\PrismicIO\ViewModel\DocumentResolver;
use Elgentos\PrismicIO\ViewModel\LinkResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use Elgentos\AlternateUrlsPrismic\Type\PrismicPage;

/**
 * @coversDefaultClass \Elgentos\AlternateUrlsPrismic\Type\PrismicPage
 */
class PrismicPageTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getAlternateUrls
     */
    public function testGetAlternateUrls(): void
    {
        $subject = new PrismicPage(
            $this->createMock(Json::class),
            $this->createMock(ScopeConfigInterface::class),
            $this->createMock(StoreManagerInterface::class),
            $this->createMock(RequestInterface::class),
            $this->createMock(DocumentResolver::class),
            $this->createMock(LinkResolver::class)
        );

        $this->assertIsArray($subject->getAlternateUrls());
    }
}
