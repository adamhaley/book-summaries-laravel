<?php

declare(strict_types=1);

namespace Features\Support\Page;

/**
 * Page Object for the Login page.
 */
final class LoginPage extends BasePage
{
    private const SELECTORS = [
        'email' => 'input[name="email"], #email',
        'password' => 'input[name="password"], #password',
        'submit' => 'button[type="submit"]',
        'error' => '[data-testid="error"], .error, [role="alert"]',
        'signUpLink' => 'a[href*="register"]',
    ];

    public function getPath(): string
    {
        return '/login';
    }

    /**
     * Fill in login credentials.
     */
    public function fillCredentials(string $email, string $password): self
    {
        $this->fillField(self::SELECTORS['email'], $email);
        $this->fillField(self::SELECTORS['password'], $password);

        return $this;
    }

    /**
     * Submit the login form.
     */
    public function submit(): self
    {
        $this->click(self::SELECTORS['submit']);
        $this->waitForPageLoad();

        return $this;
    }

    /**
     * Perform a complete login.
     */
    public function loginAs(string $email, string $password): self
    {
        return $this->fillCredentials($email, $password)->submit();
    }

    /**
     * Check if an error is displayed.
     */
    public function hasError(): bool
    {
        return $this->hasElement(self::SELECTORS['error']);
    }

    /**
     * Get the error message text.
     */
    public function getErrorMessage(): ?string
    {
        return $this->getText(self::SELECTORS['error']);
    }

    /**
     * Navigate to the sign up page.
     */
    public function goToSignUp(): void
    {
        $this->click(self::SELECTORS['signUpLink']);
        $this->waitForPageLoad();
    }
}
