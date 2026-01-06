<?php

declare(strict_types=1);

namespace Features\Support\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * Laravel Context for database seeding and app setup.
 *
 * This context provides Laravel-specific functionality for E2E tests,
 * including database transactions and test user creation.
 */
final class LaravelContext implements Context
{
    private ?Application $app = null;

    /**
     * @BeforeScenario
     */
    public function bootstrapLaravel(BeforeScenarioScope $scope): void
    {
        $this->app = $this->createApplication();
    }

    /**
     * @AfterScenario
     */
    public function cleanupDatabase(): void
    {
        // Rollback any database changes if using transactions
        if ($this->app) {
            $this->app->make('db')->rollBack();
        }
    }

    /**
     * @BeforeScenario @database
     */
    public function beginDatabaseTransaction(): void
    {
        if ($this->app) {
            $this->app->make('db')->beginTransaction();
        }
    }

    /**
     * Create and bootstrap the Laravel application.
     */
    private function createApplication(): Application
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Get the Laravel application instance.
     */
    public function getApp(): Application
    {
        if (!$this->app) {
            $this->app = $this->createApplication();
        }

        return $this->app;
    }

    /**
     * Create a test user and return the model.
     *
     * @param array<string, mixed> $attributes
     */
    public function createUser(array $attributes = []): mixed
    {
        $factory = $this->getApp()->make(\Database\Factories\UserFactory::class);

        return $factory->create($attributes);
    }

    /**
     * Run an artisan command.
     *
     * @param array<string, mixed> $parameters
     */
    public function artisan(string $command, array $parameters = []): int
    {
        return $this->getApp()->make(Kernel::class)->call($command, $parameters);
    }

    /**
     * Seed the database with specific seeders.
     *
     * @param string[] $seeders
     */
    public function seed(array $seeders = []): void
    {
        foreach ($seeders as $seeder) {
            $this->artisan('db:seed', ['--class' => $seeder]);
        }
    }
}

