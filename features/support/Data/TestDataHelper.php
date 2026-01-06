<?php

declare(strict_types=1);

namespace Features\Support\Data;

/**
 * Helper class for managing test data fixtures.
 *
 * Use this class to define and access common test data
 * used across feature tests.
 */
final class TestDataHelper
{
    /**
     * Default test user credentials.
     */
    public const TEST_USER = [
        'email' => 'test@example.com',
        'password' => 'password',
        'name' => 'Test User',
    ];

    /**
     * Unconfirmed test user credentials.
     */
    public const UNCONFIRMED_USER = [
        'email' => 'unconfirmed@example.com',
        'password' => 'testpassword123',
        'name' => 'Unconfirmed User',
    ];

    /**
     * Sample book data for testing.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getSampleBooks(): array
    {
        return [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'genre' => 'Classic',
                'year' => 1925,
                'has_default_summary' => true,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'genre' => 'Dystopian',
                'year' => 1949,
                'has_default_summary' => false,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'genre' => 'Classic',
                'year' => 1960,
                'has_default_summary' => true,
            ],
        ];
    }

    /**
     * Summary style options.
     *
     * @return string[]
     */
    public static function getSummaryStyles(): array
    {
        return ['Narrative', 'Bullet Points', 'Workbook'];
    }

    /**
     * Summary length options.
     *
     * @return string[]
     */
    public static function getSummaryLengths(): array
    {
        return ['Short', 'Medium', 'Long'];
    }

    /**
     * Generate a random email for testing.
     */
    public static function randomEmail(): string
    {
        return sprintf('test_%s@example.com', bin2hex(random_bytes(4)));
    }

    /**
     * Generate a valid password for testing.
     */
    public static function validPassword(): string
    {
        return 'SecurePassword123!';
    }
}

