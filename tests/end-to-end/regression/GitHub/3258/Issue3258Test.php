<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @preserveGlobalState
 * @runClassInSeperateProcess
 */
class Issue3258Test extends \PHPUnit\Framework\TestCase
{
    protected $internalCounter = 0;

    /**
     */
    public function testVerifyUntouchedStartStateThenUpdateState()
    {
        $this->assertSame(0, $this->internalCounter);
        $this->assertArrayNotHasKey('Issue3258_test_global', $GLOBALS);

        $this->internalCounter++;
        $GLOBALS['Issue3258_test_global'] = true;
    }

    /**
     * @depends testVerifyUntouchedStartStateThenUpdateState
     */
    public function testStateHasBeenKeptBetweenTests()
    {
        $this->assertSame(1, $this->internalCounter);
        $this->assertSame(true, $GLOBALS['Issue3258_test_global']);
    }
}
