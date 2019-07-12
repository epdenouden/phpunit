<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use PHPUnit\Framework\TestCase;

class DataProviderInstanceTest extends TestCase
{
    /**
     * @dataProvider objectHashProvider
     */
    public function testDataProviderUsesRunningTestInstance(string $hash): void
    {
        $this->assertEquals($hash, \spl_object_hash($this));
    }

    public function objectHashProvider(): array
    {
        return [
            [\spl_object_hash($this)],
        ];
    }
}
