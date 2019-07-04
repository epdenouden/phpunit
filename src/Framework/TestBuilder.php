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

use PHPUnit\Util\Test as TestUtil;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class TestBuilder
{
    public function build(\ReflectionClass $theClass, string $methodName): Test
    {
        $className = $theClass->getName();

        if (!$theClass->isInstantiable()) {
            return new WarningTestCase(
                \sprintf('Cannot instantiate class "%s".', $className)
            );
        }

        $backupSettings = TestUtil::getBackupSettings(
            $className,
            $methodName
        );

        $preserveGlobalState = TestUtil::getPreserveGlobalStateSettings(
            $className,
            $methodName
        );

        $runTestInSeparateProcess = TestUtil::getProcessIsolationSettings(
            $className,
            $methodName
        );

        $runClassInSeparateProcess = TestUtil::getClassProcessIsolationSettings(
            $className,
            $methodName
        );

        $constructor = $theClass->getConstructor();

        if ($constructor === null) {
            throw new Exception('No valid test provided.');
        }

        $parameters = $constructor->getParameters();

        // TestCase() or TestCase($name)
        if (\count($parameters) < 2) {
            $test = $this->buildTestWithoutData($className);
        } // TestCase($name, $data)
        else {
            // Test method with @dataProvider or @testWith
            if (TestUtil::hasDataProviders($className, $methodName)) {
                $test = $this->buildDataProviderTestSuite(
                    $methodName,
                    $className,
                    $runTestInSeparateProcess,
                    $preserveGlobalState,
                    $runClassInSeparateProcess,
                    $backupSettings
                );
            } else {
                $test = $this->buildTestWithoutData($className);
            }
        }

        if ($test instanceof TestCase) {
            $test->setName($methodName);
            $this->configureTestCase(
                $test,
                $runTestInSeparateProcess,
                $preserveGlobalState,
                $runClassInSeparateProcess,
                $backupSettings
            );
        }

        return $test;
    }

    private function buildTestWithoutData(string $className)
    {
        return new $className;
    }

    private function buildDataProviderTestSuite(
        string $methodName,
        string $className,
        bool $runTestInSeparateProcess,
        ?bool $preserveGlobalState,
        bool $runClassInSeparateProcess,
        array $backupSettings
    ): DataProviderTestSuite {
        $dataProviderTestSuite = new DataProviderTestSuite(
            $className . '::' . $methodName
        );

        $dataProviderTestSuite->setTestOptions(
            [
                'runTestInSeparateProcess'  => $runTestInSeparateProcess,
                'runClassInSeparateProcess' => $runClassInSeparateProcess,
                'preserveGlobalState'       => $preserveGlobalState,
                'backupGlobals'             => $backupSettings['backupGlobals'],
                'backupStaticAttributes'    => $backupSettings['backupStaticAttributes'],
            ]
        );

        return $dataProviderTestSuite;
    }

    private function configureTestCase(
        TestCase $test,
        bool $runTestInSeparateProcess,
        ?bool $preserveGlobalState,
        bool $runClassInSeparateProcess,
        array $backupSettings
    ): void {
        if ($runTestInSeparateProcess) {
            $test->setRunTestInSeparateProcess(true);

            if ($preserveGlobalState !== null) {
                $test->setPreserveGlobalState($preserveGlobalState);
            }
        }

        if ($runClassInSeparateProcess) {
            $test->setRunClassInSeparateProcess(true);

            if ($preserveGlobalState !== null) {
                $test->setPreserveGlobalState($preserveGlobalState);
            }
        }

        if ($backupSettings['backupGlobals'] !== null) {
            $test->setBackupGlobals($backupSettings['backupGlobals']);
        }

        if ($backupSettings['backupStaticAttributes'] !== null) {
            $test->setBackupStaticAttributes(
                $backupSettings['backupStaticAttributes']
            );
        }
    }
}
