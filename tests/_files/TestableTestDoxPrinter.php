<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TestFixture;

use PHPUnit\Util\TestDox\TestDoxPrinter;

class TestableTestDoxPrinter extends TestDoxPrinter
{
    /**
     * @var array<string>
     */
    private $buffer = [];

    public function write(string $text): void
    {
        $this->buffer[] = $text;
    }

    /**
     * @return array<string>
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }

    /**
     * @return array<string>
     */
    public function getTestSuiteStack(): array
    {
        return $this->testSuiteStack;
    }

    /**
     * return array<string>.
     */
    public function getCompletedTestSuites(): array
    {
        return $this->completedTestSuites;
    }
}
