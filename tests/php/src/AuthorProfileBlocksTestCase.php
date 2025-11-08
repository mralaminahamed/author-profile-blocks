<?php

namespace AuthorProfileBlocks\Test;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Abstract base class for Author Profile Blocks unit test cases.
 *
 * PHPUnit Docs: @see https://docs.phpunit.de/en/9.6/
 * Mockery: @see http://docs.mockery.io/en/latest/
 */
abstract class AuthorProfileBlocksTestCase extends TestCase {
    use MockeryPHPUnitIntegration;
}