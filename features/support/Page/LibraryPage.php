<?php

declare(strict_types=1);

namespace Features\Support\Page;

use Symfony\Component\Panther\DomCrawler\Crawler;

/**
 * Page Object for the Library page.
 */
final class LibraryPage extends BasePage
{
    private const SELECTORS = [
        'bookList' => '[data-testid="book-list"], .book-list, table tbody',
        'bookRow' => '[data-testid="book-row"], table tbody tr, .book-card',
        'bookCover' => '[data-testid="book-cover"], .book-cover img',
        'bookTitle' => '[data-testid="book-title"], .book-title',
        'bookAuthor' => '[data-testid="book-author"], .book-author',
        'getSummaryBtn' => '[data-testid="get-summary"]',
        'customizeBtn' => '[data-testid="customize"]',
        'pagination' => '[data-testid="pagination"], .pagination',
        'emptyState' => '[data-testid="empty-state"]',
        'columnHeader' => 'th button, th[data-sortable]',
        'modal' => '[data-testid="modal"], [role="dialog"], .modal',
    ];

    public function getPath(): string
    {
        return '/dashboard/library';
    }

    /**
     * Get the number of books displayed.
     */
    public function getBookCount(): int
    {
        return $this->findAll(self::SELECTORS['bookRow'])->count();
    }

    /**
     * Check if the book list is visible.
     */
    public function hasBooks(): bool
    {
        return $this->hasElement(self::SELECTORS['bookList']) && $this->getBookCount() > 0;
    }

    /**
     * Check if empty state is shown.
     */
    public function isEmptyStateVisible(): bool
    {
        return $this->hasElement(self::SELECTORS['emptyState']);
    }

    /**
     * Click on a book cover by index (0-based).
     */
    public function clickBookCover(int $index = 0): void
    {
        $covers = $this->findAll(self::SELECTORS['bookCover']);
        if ($covers->count() > $index) {
            $covers->eq($index)->click();
            $this->waitForElement(self::SELECTORS['modal']);
        }
    }

    /**
     * Click "Get Summary" button for a book by index.
     */
    public function clickGetSummary(int $index = 0): void
    {
        $buttons = $this->findAll(self::SELECTORS['getSummaryBtn']);
        if ($buttons->count() > $index) {
            $buttons->eq($index)->click();
            $this->waitForElement(self::SELECTORS['modal']);
        }
    }

    /**
     * Click a column header to sort.
     */
    public function sortByColumn(string $columnName): void
    {
        $headers = $this->findAll('th');
        $headers->each(function (Crawler $node) use ($columnName) {
            if (str_contains($node->text(), $columnName)) {
                $button = $node->filter('button');
                if ($button->count() > 0) {
                    $button->click();
                } else {
                    $node->click();
                }

                return false; // Stop iteration
            }

            return true;
        });
        $this->waitForPageLoad();
    }

    /**
     * Navigate to a specific pagination page.
     */
    public function goToPage(int $pageNumber): void
    {
        $pageLinks = $this->findAll("[data-testid='pagination'] a, .pagination a");
        $pageLinks->each(function (Crawler $node) use ($pageNumber) {
            if ($node->text() === (string) $pageNumber) {
                $node->click();

                return false;
            }

            return true;
        });
        $this->waitForPageLoad();
    }

    /**
     * Check if modal is visible.
     */
    public function isModalVisible(): bool
    {
        return $this->hasElement(self::SELECTORS['modal']);
    }

    /**
     * Get book titles as array.
     *
     * @return string[]
     */
    public function getBookTitles(): array
    {
        $titles = [];
        $this->findAll(self::SELECTORS['bookTitle'])->each(function (Crawler $node) use (&$titles) {
            $titles[] = $node->text();
        });

        return $titles;
    }
}
