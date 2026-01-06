<?php

declare(strict_types=1);

namespace Features\Support\Page;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

/**
 * Base Page Object class for reusable page interactions.
 *
 * Extend this class to create page-specific objects that encapsulate
 * element selectors and page-specific behaviors.
 */
abstract class BasePage
{
    protected Client $client;
    protected string $baseUrl;

    public function __construct(Client $client, string $baseUrl = 'http://localhost:8000')
    {
        $this->client = $client;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Get the page path (relative URL).
     */
    abstract public function getPath(): string;

    /**
     * Navigate to this page.
     */
    public function open(): self
    {
        $this->client->request('GET', $this->baseUrl . $this->getPath());
        $this->waitForPageLoad();

        return $this;
    }

    /**
     * Check if the current page matches this page object.
     */
    public function isOpen(): bool
    {
        $currentUrl = $this->client->getCurrentURL();

        return str_contains($currentUrl, $this->getPath());
    }

    /**
     * Get the DOM crawler for the current page.
     */
    protected function getCrawler(): Crawler
    {
        return $this->client->getCrawler();
    }

    /**
     * Find an element by CSS selector.
     */
    protected function find(string $selector): ?Crawler
    {
        $result = $this->getCrawler()->filter($selector);

        return $result->count() > 0 ? $result->first() : null;
    }

    /**
     * Find all elements by CSS selector.
     */
    protected function findAll(string $selector): Crawler
    {
        return $this->getCrawler()->filter($selector);
    }

    /**
     * Click an element by CSS selector.
     */
    protected function click(string $selector): void
    {
        $element = $this->find($selector);
        if ($element) {
            $element->click();
        }
    }

    /**
     * Fill a field by name or CSS selector.
     */
    protected function fillField(string $selector, string $value): void
    {
        $field = $this->find($selector);
        if ($field) {
            $field->sendKeys($value);
        }
    }

    /**
     * Get text content of an element.
     */
    protected function getText(string $selector): ?string
    {
        $element = $this->find($selector);

        return $element?->text();
    }

    /**
     * Check if an element exists.
     */
    protected function hasElement(string $selector): bool
    {
        return $this->find($selector) !== null;
    }

    /**
     * Wait for the page to fully load.
     */
    protected function waitForPageLoad(int $timeout = 10): void
    {
        $this->client->waitFor('body', $timeout);
    }

    /**
     * Wait for an element to appear.
     */
    protected function waitForElement(string $selector, int $timeout = 10): bool
    {
        try {
            $this->client->waitFor($selector, $timeout);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Wait for an element to disappear.
     */
    protected function waitForElementToDisappear(string $selector, int $timeout = 10): bool
    {
        try {
            $this->client->waitForInvisibility($selector, $timeout);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Wait for text to appear on the page.
     */
    protected function waitForText(string $text, int $timeout = 10): bool
    {
        $start = time();
        while (time() - $start < $timeout) {
            if (str_contains($this->getCrawler()->filter('body')->text(), $text)) {
                return true;
            }
            usleep(100000); // 100ms
        }

        return false;
    }
}
