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
        $this->loadData();

        return parent::run($result);
    }

    public function injectFilter(Factory $filter): void
    {
        $this->iteratorFilter = $filter;

//        print "%%% load() in injectFilter()\n";
        $this->loadData();
    }

    public function count($preferCache = false): int
    {
        if (!$this->isLoaded) {
            // At least report a test count of 1 for unloaded dataproviders
            // to ensure the test is visible to the TestRunner
            return 1;
        }

        return parent::count($preferCache);
    }

    public function loadData(): void
    {
        if ($this->isLoaded) {
            return;
        }

//        print "### loading dataprovider {$this->getName()}\n";
        $this->isLoaded = true;

        [$className, $name] = \explode('::', $this->getName());

        try {
            $data = \PHPUnit\Util\Test::getProvidedData(
                $className,
                $name
            );

            $this->createTestsFromData($className, $name, $data);
        } catch (IncompleteTestError $e) {
            $message = \sprintf(
                'Test for %s::%s marked incomplete by data provider',
                $className,
                $name
            );

            $_message = $e->getMessage();

            if (!empty($_message)) {
                $message .= "\n" . $_message;
            }

            throw new IncompleteTestError($message);
        } catch (SkippedTestError $e) {
            $message = \sprintf(
                'Test for %s::%s skipped by data provider',
                $className,
                $name
            );

            $_message = $e->getMessage();

            if (!empty($_message)) {
                $message .= "\n" . $_message;
            }

            throw new SkippedTestError($message);
        } catch (Throwable $t) {
            $message = \sprintf(
                'The data provider specified for %s::%s is invalid.',
                $className,
                $name
            );

            $_message = $t->getMessage();

            if (!empty($_message)) {
                $message .= "\n" . $_message;
            }

            throw new Warning($message);
        }

        if (empty($data)) {
            throw new Warning(
                \sprintf(
                    'No tests found in suite "%s".',
                    $this->getName()
                )
            );
        }
    }

    private function createTestsFromData(string $className, string $name, array $data): void
    {
        if (empty($data)) {
            return;
        }

        $groups = \PHPUnit\Util\Test::getGroups($className, $name);

        $runTestInSeparateProcess                 = false;
        $preserveGlobalState                      = false;
        $runClassInSeparateProcess                = false;
        $backupSettings['backupGlobals']          = false;
        $backupSettings['backupStaticAttributes'] = false;

        foreach ($data as $_dataName => $_data) {
            $_test = new $className($name, $_data, $_dataName);

            /* @var TestCase $_test */

            if ($runTestInSeparateProcess) {
                $_test->setRunTestInSeparateProcess(true);

                if ($preserveGlobalState !== null) {
                    $_test->setPreserveGlobalState($preserveGlobalState);
                }
            }

            if ($runClassInSeparateProcess) {
                $_test->setRunClassInSeparateProcess(true);

                if ($preserveGlobalState !== null) {
                    $_test->setPreserveGlobalState($preserveGlobalState);
                }
            }

            if ($backupSettings['backupGlobals'] !== null) {
                $_test->setBackupGlobals(
                    $backupSettings['backupGlobals']
                );
            }

            if ($backupSettings['backupStaticAttributes'] !== null) {
                $_test->setBackupStaticAttributes(
                    $backupSettings['backupStaticAttributes']
                );
            }

            $this->addTest($_test, $groups);
        }
    }
}
