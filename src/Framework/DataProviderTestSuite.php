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

    /**
     * @var int
     */
    private $numRowsLoaded = 0;

    /**
     * @var array
     */
    private $testOptions = [
        'runTestInSeparateProcess'  => null,
        'runClassInSeparateProcess' => null,
        'preserveGlobalState'       => null,
        'backupGlobals'             => null,
        'backupStaticAttributes'    => null,
    ];

    public function setTestOptions(array $options): void
    {
        $this->testOptions = \array_replace($this->testOptions, $options);
    }

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

        $this->isLoaded     = true;

        [$className, $name] = \explode('::', $this->getName());
        $groups             = \PHPUnit\Util\Test::getGroups($className, $name);

        try {
            $this->createTestsFromData($className, $name, $groups, $this->data());

            if ($this->numRowsLoaded === 0) {
                $message = \sprintf(
                    'No tests found in suite "%s".',
                    $this->getName()
                );
                $this->addTest(new WarningTestCase($message), $groups);
            }

            return;
        } catch (IncompleteTestError $e) {
            $message = \sprintf(
                'Test for %s::%s marked incomplete by data provider',
                $className,
                $name
            );
            $message = $this->appendExceptionMessageIfAvailable($e, $message);
            $this->addTest(new IncompleteTestCase($className, $name, $message));
        } catch (SkippedTestError $e) {
            $message = \sprintf(
                'Test for %s::%s skipped by data provider',
                $className,
                $name
            );
            $message = $this->appendExceptionMessageIfAvailable($e, $message);
            $this->addTest(new SkippedTestCase($className, $name, $message));
        } catch (\Throwable $t) {
            $message = \sprintf(
                'The data provider specified for %s::%s is invalid.',
                $className,
                $name
            );
            $message = $this->appendExceptionMessageIfAvailable($t, $message);
            $this->addTest(new WarningTestCase($message));
        }
    }

    private function data(): \Generator
    {
        [$className, $methodName] = \explode('::', $this->getName());

        $data = \PHPUnit\Util\Test::getProvidedData(
            $className,
            $methodName
        );

        foreach ($data as $key => $value) {
            if (!\is_array($value)) {
                throw new Exception(
                    \sprintf(
                        'Data set %s is invalid.',
                        \is_int($key) ? '#' . $key : '"' . $key . '"'
                    )
                );
            }

            $this->numRowsLoaded++;

            yield $key => $value;
        }
    }

    private function createTestsFromData(
        string $className,
        string $name,
        array $groups,
        \Traversable $data
    ): void {
        foreach ($data as $_dataName => $_data) {
            $_test = new $className($name, $_data, $_dataName);

            \assert($_test instanceof TestCase);

            if ($this->testOptions['runTestInSeparateProcess']) {
                $_test->setRunTestInSeparateProcess(true);

                if ($this->testOptions['preserveGlobalState'] !== null) {
                    $_test->setPreserveGlobalState($this->testOptions['preserveGlobalState']);
                }
            }

            if ($this->testOptions['runClassInSeparateProcess']) {
                $_test->setRunClassInSeparateProcess(true);

                if ($this->testOptions['preserveGlobalState'] !== null) {
                    $_test->setPreserveGlobalState($this->testOptions['preserveGlobalState']);
                }
            }

            if ($this->testOptions['backupGlobals'] !== null) {
                $_test->setBackupGlobals($this->testOptions['backupGlobals']);
            }

            if ($this->testOptions['backupStaticAttributes'] !== null) {
                $_test->setBackupStaticAttributes($this->testOptions['backupStaticAttributes']);
            }
            $this->addTest($_test, $groups);
        }
    }

    private function appendExceptionMessageIfAvailable(\Throwable $e, string $message): string
    {
        $_message = $e->getMessage();

        if (!empty($_message)) {
            $message .= "\n" . $_message;
        }

        return $message;
    }
}
