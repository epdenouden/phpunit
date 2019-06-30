<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class SyntheticDataProviderLoadingTest extends \PHPUnit\Framework\TestCase
{
    public const SIZE_SMALL = 10000;

    public const SIZE_MEDIUM = 50000;

    public const SIZE_LARGE = 100000;

    public function testFirst(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider smallProvider
     */
    public function testSmall(string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium(string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge(string $text): void
    {
        $this->assertIsString($text);
    }

    public function testLast(): void
    {
        $this->assertTrue(true);
    }

    public function smallProvider(): \Traversable
    {
        for ($i = 0; $i < 3; $i++) {
            yield "small $i" => [\str_repeat('some string', self::SIZE_SMALL)];
        }
    }

    public function mediumProvider(): \Traversable
    {
        for ($i = 0; $i < 5; $i++) {
            yield "medium $i" => [\str_repeat('some string', self::SIZE_MEDIUM)];
        }
    }

    public function largeProvider(): \Traversable
    {
        for ($i = 0; $i < 10; $i++) {
            yield "large $i" => [\str_repeat('some string', self::SIZE_LARGE)];
        }
    }
}
