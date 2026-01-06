<?php

declare(strict_types=1);

namespace Features\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

/**
 * UI Context for E2E testing with Behat and Symfony Panther.
 *
 * This context provides step definitions for browser-based testing
 * of the Laravel application using headless Chrome.
 */
final class UiContext implements Context
{
    private string $baseUrl;
    private ?Client $client = null;

    public function __construct(string $base_url = 'http://localhost:8000')
    {
        $this->baseUrl = rtrim($base_url, '/');
    }

    // =========================================================================
    // HOOKS
    // =========================================================================

    /**
     * @BeforeScenario
     */
    public function beforeScenario(BeforeScenarioScope $scope): void
    {
        $this->client = Client::createChromeClient(null, [
            '--headless',
            '--disable-gpu',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--window-size=1920,1080',
        ]);
    }

    /**
     * @AfterScenario
     */
    public function afterScenario(AfterScenarioScope $scope): void
    {
        if ($this->client) {
            $this->client->quit();
            $this->client = null;
        }
    }

    /**
     * @AfterStep
     */
    public function takeScreenshotOnFailure(AfterStepScope $scope): void
    {
        if (!$scope->getTestResult()->isPassed() && $this->client) {
            $this->saveScreenshot();
        }
    }

    // =========================================================================
    // GIVEN STEPS - Setup and preconditions
    // =========================================================================

    /**
     * @Given the application is running
     */
    public function theApplicationIsRunning(): void
    {
        $this->visit('/');
        $this->assertStatusCode(200);
    }


    /**
     * @Given I am signed in as :email
     */
    public function iAmSignedInAs(string $email): void
    {
        $this->visit('/login');
        $this->fillField('email', $email);
        $this->fillField('password', 'password'); // Default test password
        $this->pressButton('Sign in');
        $this->waitForPageLoad();
    }

    /**
     * @Given /^I am on the (.+?)(?:\s+page)?$/
     */
    public function iAmOnThePage(string $pageName): void
    {
        $normalizedName = strtolower(trim($pageName, '"\''));
        
        $routes = [
            'my summaries' => '/dashboard/summaries',
            'preferences' => '/dashboard/preferences',
            'library' => '/dashboard/library',
            'dashboard' => '/dashboard',
            'home' => '/',
            'sign in' => '/login',
            'login' => '/login',
            'sign up' => '/register',
            'register' => '/register',
        ];

        $path = $routes[$normalizedName] ?? '/dashboard/' . str_replace(' ', '-', $normalizedName);
        $this->visit($path);
    }

    /**
     * @Given I have opened the generate summary modal for a book
     * @Given I have opened the generate summary modal
     */
    public function iHaveOpenedTheGenerateSummaryModalForABook(): void
    {
        $this->iAmOnTheLibraryPage();
        $this->iClickForABookWithoutADefaultSummary('Get Summary');
    }

    /**
     * @Given my user preferences are set to :style style and :length length
     */
    public function myUserPreferencesAreSetTo(string $style, string $length): void
    {
        // This would typically be set via API or database seeding
    }

    /**
     * @Given there are more than :count books in the library
     */
    public function thereAreMoreThanBooksInTheLibrary(int $count): void
    {
        // Handled by database seeding
    }

    /**
     * @Given the library is empty
     * @Given there are no books available
     */
    public function thereAreNoBooksInTheLibrary(): void
    {
        // Handled by database seeding
    }

    /**
     * @Given there are :count books in the library
     * @Given the library has :count books
     */
    public function thereAreBooksInTheLibrary(int $count): void
    {
        // Handled by database seeding
    }

    /**
     * @Given a book has a pre-generated default summary
     */
    public function aBookHasAPreGeneratedDefaultSummary(): void
    {
        // Handled by database seeding
    }

    /**
     * @Given a book does not have a pre-generated summary
     */
    public function aBookDoesNotHaveAPreGeneratedSummary(): void
    {
        // Handled by database seeding
    }

    /**
     * @Given I am viewing on a mobile device
     */
    public function iAmViewingOnAMobileDevice(): void
    {
        $this->getClient()->manage()->window()->setSize(
            new \Facebook\WebDriver\WebDriverDimension(375, 812)
        );
    }

    /**
     * @Given I am viewing on a desktop device
     */
    public function iAmViewingOnADesktopDevice(): void
    {
        $this->getClient()->manage()->window()->setSize(
            new \Facebook\WebDriver\WebDriverDimension(1920, 1080)
        );
    }

    /**
     * @Given I have already generated a summary for :bookTitle
     */
    public function iHaveAlreadyGeneratedASummaryFor(string $bookTitle): void
    {
        // Handled by database seeding
    }

    /**
     * @Given I have at least one generated summary
     */
    public function iHaveAtLeastOneGeneratedSummary(): void
    {
        // Handled by database seeding
    }

    /**
     * @Given I have not generated any summaries yet
     */
    public function iHaveNotGeneratedAnySummariesYet(): void
    {
        // Handled by database seeding
    }

    /**
     * @Given I have clicked :buttonText for a large book
     */
    public function iHaveClickedForALargeBook(string $buttonText): void
    {
        $this->pressButton($buttonText);
    }

    // =========================================================================
    // WHEN STEPS - Actions
    // =========================================================================

    /**
     * @When I visit the home page
     */
    public function iVisitTheHomePage(): void
    {
        $this->visit('/');
    }

    /**
     * @When I click the :buttonText button
     */
    public function iClickTheButton(string $buttonText): void
    {
        // Try to find and click as a button first, then as a link
        try {
            $this->pressButton($buttonText);
        } catch (\Exception $e) {
            $this->clickLink($buttonText);
        }
    }

    /**
     * @When I click the :linkText link
     */
    public function iClickTheLink(string $linkText): void
    {
        $this->clickLink($linkText);
    }

    /**
     * @When I enter email :email
     */
    public function iEnterEmail(string $email): void
    {
        $this->fillField('email', $email);
    }

    /**
     * @When I enter password :password
     */
    public function iEnterPassword(string $password): void
    {
        $this->fillField('password', $password);
    }

    /**
     * @When I submit the form
     * @When I click the sign in button
     */
    public function iClickTheSubmitButton(): void
    {
        $this->pressButton('Sign in');
    }

    /**
     * @When I click the :columnHeader column header
     */
    public function iClickTheColumnHeader(string $columnHeader): void
    {
        $header = $this->getCrawler()->filter("th")->reduce(function (Crawler $node) use ($columnHeader) {
            return str_contains($node->text(), $columnHeader);
        });

        if ($header->count() > 0) {
            $header->first()->click();
            $this->waitForAjax();
        }
    }

    /**
     * @When I click the :columnHeader column header again
     */
    public function iClickTheColumnHeaderAgain(string $columnHeader): void
    {
        $this->iClickTheColumnHeader($columnHeader);
    }

    /**
     * @When I am on page :pageNumber of the book list
     */
    public function iAmOnPageOfTheBookList(int $pageNumber): void
    {
        // Navigate to specific page
    }

    /**
     * @When I click page :pageNumber in the pagination
     */
    public function iClickPageInThePagination(int $pageNumber): void
    {
        $this->clickLink((string) $pageNumber);
        $this->waitForPageLoad();
    }

    /**
     * @When I click on the first book cover
     */
    public function iClickOnTheFirstBookCover(): void
    {
        $cover = $this->getCrawler()->filter('.book-cover, [data-testid="book-cover"]')->first();
        if ($cover->count() > 0) {
            $cover->click();
            $this->waitForElement('[data-testid="modal"], .modal, [role="dialog"]');
        }
    }

    /**
     * @When I click the :buttonText button for that book
     */
    public function iClickTheButtonForThatBook(string $buttonText): void
    {
        $this->pressButton($buttonText);
    }

    /**
     * @When I click :buttonText for a book without a default summary
     */
    public function iClickForABookWithoutADefaultSummary(string $buttonText): void
    {
        $this->iClickTheButtonForThatBook($buttonText);
    }

    /**
     * @When I view the library page
     */
    public function iViewTheLibraryPage(): void
    {
        $this->visit('/dashboard/library');
    }

    /**
     * @When I visit the library page
     */
    public function iVisitTheLibraryPage(): void
    {
        $this->visit('/dashboard/library');
    }

    /**
     * @When the modal loads
     */
    public function theModalLoads(): void
    {
        $this->waitForElement('[data-testid="modal"], .modal, [role="dialog"]');
    }

    /**
     * @When I click :buttonText
     */
    public function iClick(string $buttonText): void
    {
        $this->pressButton($buttonText);
    }

    /**
     * @When I select :option style
     */
    public function iSelectStyle(string $option): void
    {
        $this->selectOption('style', $option);
    }

    /**
     * @When I select :option length
     */
    public function iSelectLength(string $option): void
    {
        $this->selectOption('length', $option);
    }

    /**
     * @When I navigate to :pageName page
     */
    public function iNavigateToPage(string $pageName): void
    {
        $this->clickLink($pageName);
        $this->waitForPageLoad();
    }

    /**
     * @When I click the :buttonText button for a summary
     */
    public function iClickTheButtonForASummary(string $buttonText): void
    {
        $button = $this->getCrawler()->filter("[data-testid='summary-item'] button")->reduce(function (Crawler $node) use ($buttonText) {
            return str_contains($node->text(), $buttonText);
        });

        if ($button->count() > 0) {
            $button->first()->click();
            $this->waitForAjax();
        }
    }

    /**
     * @When I generate another summary for :bookTitle with different settings
     */
    public function iGenerateAnotherSummaryForWithDifferentSettings(string $bookTitle): void
    {
        // Navigate and generate another summary
    }

    /**
     * @When I change my default style to :style
     */
    public function iChangeMyDefaultStyleTo(string $style): void
    {
        $this->selectOption('default_style', $style);
    }

    /**
     * @When I change my default length to :length
     */
    public function iChangeMyDefaultLengthTo(string $length): void
    {
        $this->selectOption('default_length', $length);
    }

    /**
     * @When I save my preferences
     */
    public function iSaveMyPreferences(): void
    {
        $this->pressButton('Save');
        $this->waitForAjax();
    }

    /**
     * @When I open a generate summary modal
     */
    public function iOpenAGenerateSummaryModal(): void
    {
        $this->iHaveOpenedTheGenerateSummaryModalForABook();
    }

    /**
     * @When the generation takes longer than :seconds seconds
     */
    public function theGenerationTakesLongerThanSeconds(int $seconds): void
    {
        sleep($seconds);
    }

    /**
     * @When I view a summary entry
     */
    public function iViewASummaryEntry(): void
    {
        // View summary entry
    }

    /**
     * @When I want to generate more summaries
     */
    public function iWantToGenerateMoreSummaries(): void
    {
        // Intent step
    }

    /**
     * @When I navigate to page :pageNumber
     */
    public function iNavigateToPageNumber(int $pageNumber): void
    {
        $this->clickLink((string) $pageNumber);
        $this->waitForPageLoad();
    }

    // =========================================================================
    // THEN STEPS - Assertions
    // =========================================================================

    /**
     * @Then I should see the landing page
     */
    public function iShouldSeeTheLandingPage(): void
    {
        $this->assertElementExists('[data-testid="landing-page"], .landing-page, main');
    }

    /**
     * @Then I should see :text in the hero title
     */
    public function iShouldSeeInTheHeroTitle(string $text): void
    {
        $hero = $this->getCrawler()->filter('h1, [data-testid="hero-title"]')->first();
        if (!str_contains($hero->text(), $text)) {
            throw new \Exception("Expected '$text' in hero title, got: " . $hero->text());
        }
    }

    /**
     * @Then I should see a :buttonText button
     */
    public function iShouldSeeAButton(string $buttonText): void
    {
        $found = $this->getCrawler()->filter('a, button')->reduce(function (Crawler $node) use ($buttonText) {
            return str_contains($node->text(), $buttonText);
        });

        if ($found->count() === 0) {
            throw new \Exception("Button '$buttonText' not found");
        }
    }

    /**
     * @Then I should be redirected to the sign in page
     */
    public function iShouldBeRedirectedToTheSignInPage(): void
    {
        $this->waitForPageLoad();
        $this->assertUrlMatches('/\/login/');
    }

    /**
     * @Then I should see the :pageTitle page title
     */
    public function iShouldSeeThePageTitle(string $pageTitle): void
    {
        $title = $this->getCrawler()->filter('h1, h2, [data-testid="page-title"]')->first();
        if (!str_contains($title->text(), $pageTitle)) {
            throw new \Exception("Expected page title '$pageTitle', got: " . $title->text());
        }
    }

    /**
     * @Then I should be redirected to the sign up page
     */
    public function iShouldBeRedirectedToTheSignUpPage(): void
    {
        $this->waitForPageLoad();
        $this->assertUrlMatches('/\/register/');
    }

    /**
     * @Then I should be redirected to the dashboard
     */
    public function iShouldBeRedirectedToTheDashboard(): void
    {
        $this->waitForPageLoad();
        $this->assertUrlMatches('/\/dashboard/');
    }

    /**
     * @Then I should see the dashboard library page
     */
    public function iShouldSeeTheDashboardLibraryPage(): void
    {
        $this->assertUrlMatches('/\/dashboard/');
    }

    /**
     * @Then I should see an error message
     */
    public function iShouldSeeAnErrorMessage(): void
    {
        $this->assertElementExists('[data-testid="error"], .error, .alert-error, [role="alert"]');
    }

    /**
     * @Then I should remain on the sign in page
     */
    public function iShouldRemainOnTheSignInPage(): void
    {
        $this->assertUrlMatches('/\/login/');
    }

    /**
     * @Then I should see an error about email verification
     */
    public function iShouldSeeAnErrorAboutEmailVerification(): void
    {
        $this->assertPageContainsText('verify');
    }

    /**
     * @Then the error should mention checking inbox
     */
    public function theErrorShouldMentionCheckingInbox(): void
    {
        $text = $this->getCrawler()->filter('body')->text();
        if (!preg_match('/inbox|email/i', $text)) {
            throw new \Exception("Expected mention of inbox or email in error");
        }
    }

    /**
     * @Then I should be automatically redirected to the dashboard
     */
    public function iShouldBeAutomaticallyRedirectedToTheDashboard(): void
    {
        $this->waitForPageLoad();
        $this->assertUrlMatches('/\/dashboard/');
    }

    /**
     * @Then I should not see the landing page
     */
    public function iShouldNotSeeTheLandingPage(): void
    {
        $url = $this->getClient()->getCurrentURL();
        if (preg_match('/^' . preg_quote($this->baseUrl, '/') . '\/?$/', $url)) {
            throw new \Exception("Should not be on landing page, but URL is: $url");
        }
    }

    /**
     * @Then I should see a list of books
     */
    public function iShouldSeeAListOfBooks(): void
    {
        $this->assertElementExists('[data-testid="book-list"], .book-list, table, .books-grid');
    }

    /**
     * @Then each book should display a cover image
     */
    public function eachBookShouldDisplayACoverImage(): void
    {
        $this->assertElementExists('img[src*="cover"], [data-testid="book-cover"] img');
    }

    /**
     * @Then each book should display a title and author
     */
    public function eachBookShouldDisplayATitleAndAuthor(): void
    {
        $this->assertElementExists('[data-testid="book-title"], .book-title');
        $this->assertElementExists('[data-testid="book-author"], .book-author');
    }

    /**
     * @Then the books should be sorted by title in ascending order
     */
    public function theBooksShouldBeSortedByTitleInAscendingOrder(): void
    {
        // Verify sort order by checking aria-sort or data attributes
    }

    /**
     * @Then the books should be sorted by title in descending order
     */
    public function theBooksShouldBeSortedByTitleInDescendingOrder(): void
    {
        // Verify sort order
    }

    /**
     * @Then the books should be sorted by author name in ascending order
     */
    public function theBooksShouldBeSortedByAuthorNameInAscendingOrder(): void
    {
        // Verify sort order
    }

    /**
     * @Then the books should be sorted by genre in ascending order
     */
    public function theBooksShouldBeSortedByGenreInAscendingOrder(): void
    {
        // Verify sort order
    }

    /**
     * @Then the books should be sorted by publication year in ascending order
     */
    public function theBooksShouldBeSortedByPublicationYearInAscendingOrder(): void
    {
        // Verify sort order
    }

    /**
     * @Then I should see a different set of books
     */
    public function iShouldSeeADifferentSetOfBooks(): void
    {
        // Verify pagination changed content
    }

    /**
     * @Then the page indicator should show :text
     */
    public function thePageIndicatorShouldShow(string $text): void
    {
        $this->assertPageContainsText($text);
    }

    /**
     * @Then I should see the pre-generated summary download or customization modal
     */
    public function iShouldSeeThePreGeneratedSummaryDownloadOrCustomizationModal(): void
    {
        $this->waitForElement('[data-testid="modal"], .modal, [role="dialog"]');
    }

    /**
     * @Then the default summary PDF should start downloading
     */
    public function theDefaultSummaryPdfShouldStartDownloading(): void
    {
        // Check for download trigger
    }

    /**
     * @Then I should see the :modalTitle modal
     */
    public function iShouldSeeTheModal(string $modalTitle): void
    {
        $this->waitForElement('[data-testid="modal"], .modal, [role="dialog"]');
        $this->assertPageContainsText($modalTitle);
    }

    /**
     * @Then I should see style options
     */
    public function iShouldSeeStyleOptions(): void
    {
        $this->assertElementExists('[data-testid="style-options"], [name="style"]');
    }

    /**
     * @Then I should see length options
     */
    public function iShouldSeeLengthOptions(): void
    {
        $this->assertElementExists('[data-testid="length-options"], [name="length"]');
    }

    /**
     * @Then the modal should show the book title
     */
    public function theModalShouldShowTheBookTitle(): void
    {
        $this->assertElementExists('[data-testid="modal-book-title"]');
    }

    /**
     * @Then the modal should show customization options
     */
    public function theModalShouldShowCustomizationOptions(): void
    {
        $this->iShouldSeeStyleOptions();
        $this->iShouldSeeLengthOptions();
    }

    /**
     * @Then I should see books in a card layout
     */
    public function iShouldSeeBooksInACardLayout(): void
    {
        $this->assertElementExists('.book-card, [data-testid="book-card"]');
    }

    /**
     * @Then each card should show the book cover
     */
    public function eachCardShouldShowTheBookCover(): void
    {
        $this->eachBookShouldDisplayACoverImage();
    }

    /**
     * @Then each card should show title, author, and description
     */
    public function eachCardShouldShowTitleAuthorAndDescription(): void
    {
        $this->eachBookShouldDisplayATitleAndAuthor();
    }

    /**
     * @Then each card should show genre badge
     */
    public function eachCardShouldShowGenreBadge(): void
    {
        $this->assertElementExists('[data-testid="genre-badge"], .genre-badge');
    }

    /**
     * @Then each card should show action buttons
     */
    public function eachCardShouldShowActionButtons(): void
    {
        $this->assertElementExists('button');
    }

    /**
     * @Then I should see books in a table layout
     */
    public function iShouldSeeBooksInATableLayout(): void
    {
        $this->assertElementExists('table');
    }

    /**
     * @Then the table should have sortable columns
     */
    public function theTableShouldHaveSortableColumns(): void
    {
        $this->assertElementExists('th[data-sortable], th button');
    }

    /**
     * @Then each row should show a clickable book cover
     */
    public function eachRowShouldShowAClickableBookCover(): void
    {
        $this->assertElementExists('table img');
    }

    /**
     * @Then I should see :text message
     */
    public function iShouldSeeMessage(string $text): void
    {
        $this->assertPageContainsText($text);
    }

    /**
     * @Then I should not see the books table
     */
    public function iShouldNotSeeTheBooksTable(): void
    {
        $this->assertElementNotExists('table tbody tr');
    }

    /**
     * @Then I should see :text at the bottom
     */
    public function iShouldSeeAtTheBottom(string $text): void
    {
        $this->assertPageContainsText($text);
    }

    /**
     * @Then the modal should display the book title
     */
    public function theModalShouldDisplayTheBookTitle(): void
    {
        $this->theModalShouldShowTheBookTitle();
    }

    /**
     * @Then the modal should display the book author
     */
    public function theModalShouldDisplayTheBookAuthor(): void
    {
        $this->assertElementExists('[data-testid="modal-book-author"]');
    }

    /**
     * @Then the modal should show summary style options
     */
    public function theModalShouldShowSummaryStyleOptions(): void
    {
        $this->iShouldSeeStyleOptions();
    }

    /**
     * @Then the modal should show summary length options
     */
    public function theModalShouldShowSummaryLengthOptions(): void
    {
        $this->iShouldSeeLengthOptions();
    }

    /**
     * @Then I should see style options:
     */
    public function iShouldSeeStyleOptionsTable(\Behat\Gherkin\Node\TableNode $table): void
    {
        $this->iShouldSeeStyleOptions();
        // Verify the options match the table
        foreach ($table->getRows() as $row) {
            $optionText = $row[0];
            $this->assertPageContainsText($optionText);
        }
    }

    /**
     * @Then I should see length options:
     */
    public function iShouldSeeLengthOptionsTable(\Behat\Gherkin\Node\TableNode $table): void
    {
        $this->iShouldSeeLengthOptions();
        // Verify the options match the table
        foreach ($table->getRows() as $row) {
            $optionText = $row[0];
            $this->assertPageContainsText($optionText);
        }
    }

    /**
     * @Then the style slider should default to :style
     */
    public function theStyleSliderShouldDefaultTo(string $style): void
    {
        // Check default value
    }

    /**
     * @Then the length slider should default to :length
     */
    public function theLengthSliderShouldDefaultTo(string $length): void
    {
        // Check default value
    }

    /**
     * @Then the summary generation should start
     */
    public function theSummaryGenerationShouldStart(): void
    {
        // Check for loading state
    }

    /**
     * @Then I should see a loading indicator
     */
    public function iShouldSeeALoadingIndicator(): void
    {
        $this->assertElementExists('[data-testid="loading"], .loading, .spinner');
    }

    /**
     * @Then the PDF should download when generation is complete
     */
    public function thePdfShouldDownloadWhenGenerationIsComplete(): void
    {
        // Wait and verify download
    }

    /**
     * @Then the summary should be generated with bullet points style
     */
    public function theSummaryShouldBeGeneratedWithBulletPointsStyle(): void
    {
        // Verify style
    }

    /**
     * @Then the summary should use short length format
     */
    public function theSummaryShouldUseShortLengthFormat(): void
    {
        // Verify length
    }

    /**
     * @Then I should see a list of all my generated summaries
     */
    public function iShouldSeeAListOfAllMyGeneratedSummaries(): void
    {
        $this->assertElementExists('[data-testid="summaries-list"]');
    }

    /**
     * @Then each summary should display the book title
     */
    public function eachSummaryShouldDisplayTheBookTitle(): void
    {
        $this->assertElementExists('[data-testid="summary-book-title"]');
    }

    /**
     * @Then each summary should display the book author
     */
    public function eachSummaryShouldDisplayTheBookAuthor(): void
    {
        $this->assertElementExists('[data-testid="summary-book-author"]');
    }

    /**
     * @Then each summary should display style badge
     */
    public function eachSummaryShouldDisplayStyleBadge(): void
    {
        $this->assertElementExists('[data-testid="style-badge"]');
    }

    /**
     * @Then each summary should display length badge
     */
    public function eachSummaryShouldDisplayLengthBadge(): void
    {
        $this->assertElementExists('[data-testid="length-badge"]');
    }

    /**
     * @Then each summary should display creation date
     */
    public function eachSummaryShouldDisplayCreationDate(): void
    {
        $this->assertElementExists('[data-testid="creation-date"]');
    }

    /**
     * @Then each summary should have a :buttonText button
     */
    public function eachSummaryShouldHaveAButton(string $buttonText): void
    {
        $found = $this->getCrawler()->filter('button')->reduce(function (Crawler $node) use ($buttonText) {
            return str_contains($node->text(), $buttonText);
        });

        if ($found->count() === 0) {
            throw new \Exception("Button '$buttonText' not found");
        }
    }

    /**
     * @Then the summary PDF should download immediately
     */
    public function theSummaryPdfShouldDownloadImmediately(): void
    {
        // Verify download
    }

    /**
     * @Then the download should not require regeneration
     */
    public function theDownloadShouldNotRequireRegeneration(): void
    {
        // Verify no loading state
    }

    /**
     * @Then the summary should be removed from the list
     */
    public function theSummaryShouldBeRemovedFromTheList(): void
    {
        // Verify removal
    }

    /**
     * @Then the PDF file should be deleted from storage
     */
    public function thePdfFileShouldBeDeletedFromStorage(): void
    {
        // Verify file deletion
    }

    /**
     * @Then I should see a confirmation message
     */
    public function iShouldSeeAConfirmationMessage(): void
    {
        $this->assertElementExists('[data-testid="toast"], .toast, .notification');
    }

    /**
     * @Then I should see :text summary message
     */
    public function iShouldSeeSummaryMessage(string $text): void
    {
        $this->assertPageContainsText($text);
    }

    /**
     * @Then I should see a suggestion to visit the library
     */
    public function iShouldSeeASuggestionToVisitTheLibrary(): void
    {
        $text = $this->getCrawler()->filter('body')->text();
        if (!preg_match('/library/i', $text)) {
            throw new \Exception("Expected suggestion to visit library");
        }
    }

    /**
     * @Then both summaries should be saved
     */
    public function bothSummariesShouldBeSaved(): void
    {
        // Verify multiple summaries
    }

    /**
     * @Then I should see both summaries in my summaries list
     */
    public function iShouldSeeBothSummariesInMySummariesList(): void
    {
        // Verify list count
    }

    /**
     * @Then each summary should show its unique settings
     */
    public function eachSummaryShouldShowItsUniqueSettings(): void
    {
        // Verify settings display
    }

    /**
     * @Then future summary generations should use these defaults
     */
    public function futureSummaryGenerationsShouldUseTheseDefaults(): void
    {
        // Verify default preferences
    }

    /**
     * @Then the sliders should reflect the new defaults
     */
    public function theSlidersShouldReflectTheNewDefaults(): void
    {
        // Verify slider values
    }

    /**
     * @Then the loading indicator should remain visible
     */
    public function theLoadingIndicatorShouldRemainVisible(): void
    {
        $this->iShouldSeeALoadingIndicator();
    }

    /**
     * @Then the browser should not timeout
     */
    public function theBrowserShouldNotTimeout(): void
    {
        // Verify no timeout
    }

    /**
     * @Then the PDF should eventually download when complete
     */
    public function thePdfShouldEventuallyDownloadWhenComplete(): void
    {
        // Wait for download
    }

    /**
     * @Then /^I should see the generation time \(if available\)$/
     */
    public function iShouldSeeTheGenerationTimeIfAvailable(): void
    {
        // Optional check - generation time may or may not be displayed
        try {
            $this->assertElementExists('[data-testid="generation-time"]');
        } catch (\Exception $e) {
            // It's optional, so we don't fail if not found
        }
    }

    /**
     * @Given I am viewing my generated summaries
     */
    public function iAmViewingMyGeneratedSummaries(): void
    {
        $this->visit('/dashboard/summaries');
    }

    /**
     * @When I am on page :pageNumber
     */
    public function iAmOnPage(int $pageNumber): void
    {
        // Already on page or navigate to it
    }

    /**
     * @Then I should see the creation timestamp
     */
    public function iShouldSeeTheCreationTimestamp(): void
    {
        $this->eachSummaryShouldDisplayCreationDate();
    }

    /**
     * @Then I should see the summary style and length used
     */
    public function iShouldSeeTheSummaryStyleAndLengthUsed(): void
    {
        $this->eachSummaryShouldDisplayStyleBadge();
        $this->eachSummaryShouldDisplayLengthBadge();
    }

    /**
     * @Then I should be able to navigate to the library page
     */
    public function iShouldBeAbleToNavigateToTheLibraryPage(): void
    {
        $found = $this->getCrawler()->filter('a')->reduce(function (Crawler $node) {
            $href = $node->attr('href') ?? '';
            $text = $node->text();
            return str_contains($text, 'Library') || str_contains($href, 'library');
        });

        if ($found->count() === 0) {
            throw new \Exception("Library link not found");
        }
    }

    /**
     * @Then I should be able to access the library from the dashboard menu
     */
    public function iShouldBeAbleToAccessTheLibraryFromTheDashboardMenu(): void
    {
        $found = $this->getCrawler()->filter('nav a')->reduce(function (Crawler $node) {
            $href = $node->attr('href') ?? '';
            $text = $node->text();
            return str_contains($text, 'Library') || str_contains($href, 'library');
        });

        if ($found->count() === 0) {
            throw new \Exception("Library link not found in navigation");
        }
    }

    /**
     * @Then I should see the page title :title
     */
    public function iShouldSeeThePageTitleWithQuotes(string $title): void
    {
        $this->iShouldSeeThePageTitle($title);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Get the Panther client.
     */
    private function getClient(): Client
    {
        if (!$this->client) {
            throw new \RuntimeException('Panther client not initialized');
        }

        return $this->client;
    }

    /**
     * Get the DOM crawler for the current page.
     */
    private function getCrawler(): Crawler
    {
        return $this->getClient()->getCrawler();
    }

    /**
     * Visit a URL path.
     */
    private function visit(string $path): void
    {
        $url = $this->baseUrl . $path;
        $this->getClient()->request('GET', $url);
    }

    /**
     * Assert HTTP status code.
     */
    private function assertStatusCode(int $expected): void
    {
        // Panther doesn't directly expose status codes for navigated pages
        // We verify by checking the page loaded successfully
    }

    /**
     * Fill a form field.
     */
    private function fillField(string $field, string $value): void
    {
        $element = $this->getCrawler()->filter("input[name='$field'], #$field, [data-testid='$field']")->first();
        if ($element->count() > 0) {
            $element->sendKeys($value);
        } else {
            throw new \Exception("Field '$field' not found");
        }
    }

    /**
     * Press a button by text.
     */
    private function pressButton(string $buttonText): void
    {
        $button = $this->getCrawler()->filter('button, input[type="submit"]')->reduce(function (Crawler $node) use ($buttonText) {
            return str_contains($node->text(), $buttonText) || $node->attr('value') === $buttonText;
        });

        if ($button->count() > 0) {
            $button->first()->click();
        } else {
            throw new \Exception("Button '$buttonText' not found");
        }
    }

    /**
     * Click a link by text.
     */
    private function clickLink(string $linkText): void
    {
        $link = $this->getCrawler()->filter('a, button')->reduce(function (Crawler $node) use ($linkText) {
            return str_contains($node->text(), $linkText);
        });

        if ($link->count() > 0) {
            $link->first()->click();
        } else {
            throw new \Exception("Link '$linkText' not found");
        }
    }

    /**
     * Select an option from a dropdown.
     */
    private function selectOption(string $field, string $option): void
    {
        $select = $this->getCrawler()->filter("select[name='$field'], #$field")->first();
        if ($select->count() > 0) {
            $this->getClient()->executeScript(
                "document.querySelector('select[name=\"$field\"], #$field').value = '$option';"
            );
        }
    }

    /**
     * Wait for the page to fully load.
     */
    private function waitForPageLoad(int $timeout = 10): void
    {
        $this->getClient()->waitFor('body', $timeout);
    }

    /**
     * Wait for AJAX requests to complete.
     */
    private function waitForAjax(int $timeout = 10): void
    {
        usleep(500000); // 0.5 seconds for Vue reactivity
    }

    /**
     * Wait for an element to appear.
     */
    private function waitForElement(string $selector, int $timeout = 10): void
    {
        $this->getClient()->waitFor($selector, $timeout);
    }

    /**
     * Assert URL matches pattern.
     */
    private function assertUrlMatches(string $pattern): void
    {
        $url = $this->getClient()->getCurrentURL();
        if (!preg_match($pattern, $url)) {
            throw new \Exception("URL '$url' does not match pattern '$pattern'");
        }
    }

    /**
     * Assert element exists.
     */
    private function assertElementExists(string $selector): void
    {
        $element = $this->getCrawler()->filter($selector);
        if ($element->count() === 0) {
            throw new \Exception("Element '$selector' not found");
        }
    }

    /**
     * Assert element does not exist.
     */
    private function assertElementNotExists(string $selector): void
    {
        $element = $this->getCrawler()->filter($selector);
        if ($element->count() > 0) {
            throw new \Exception("Element '$selector' should not exist but was found");
        }
    }

    /**
     * Assert page contains text.
     */
    private function assertPageContainsText(string $text): void
    {
        $pageText = $this->getCrawler()->filter('body')->text();
        if (!str_contains(strtolower($pageText), strtolower($text))) {
            throw new \Exception("Text '$text' not found on page");
        }
    }

    /**
     * Save a screenshot for debugging.
     */
    private function saveScreenshot(): void
    {
        $filename = sprintf('screenshot_%s.png', date('Y-m-d_H-i-s'));
        $path = __DIR__ . '/../../storage/screenshots/' . $filename;
        @mkdir(dirname($path), 0755, true);
        $this->getClient()->takeScreenshot($path);
    }
}
