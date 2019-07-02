<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

use PHPUnit\Runner\Filter\Factory;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class DataProviderTestSuite extends TestSuite
{
    /**
     * @var string[]
     */
    private $dependencies = [];

    /**
     * @var bool
     */
    private $isLoaded = false;

    public function injectFilter(Factory $filter): void
    {
        $this->loadDataProviders();
        parent::injectFilter($filter);
    }

    public function count($preferCache = false): int
    {
        if (!$this->isLoaded) {
            // At least report a test count of 1 for unloaded data providers
            // to ensure the test is visible to the TestRunner
            return 1;
        }

        return parent::count($preferCache);
    }

    /**
     * @param string[] $dependencies
     */
    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;

        foreach ($this->tests as $test) {
            $test->setDependencies($dependencies);
        }
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function hasDependencies(): bool
    {
        return \count($this->dependencies) > 0;
    }

    public function run(TestResult $result = null): TestResult
    {
        $this->loadDataProviders();

        return parent::run($result);
    }

    public function loadDataProviders(): void
    {
        if ($this->isLoaded) {
            return;
        }

        $this->isLoaded = true;
    }
}
