<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class DummyGeneratorDataProviderLoadingTest extends \PHPUnit\Framework\TestCase
{
    const SIZE_SMALL = 10000;
    const SIZE_MEDIUM = 50000;
    const SIZE_LARGE = 100000;

    public function testFirst(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider smallProvider
     */
    public function testSmall1 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider smallProvider
     */
    public function testSmall2 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider smallProvider
     */
    public function testSmall3 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium1 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium2 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium3 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium4 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium5 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge1 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge2 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge3 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge4 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge5 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge6 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge7 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge8 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge9 (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge10 (string $text): void
    {
        $this->assertIsString($text);
    }

    public function testLast(): void
    {
        $this->assertTrue(true);
    }

    public function smallProvider(): array
    {
        return [
            "small 1" => [str_repeat("some string", self::SIZE_SMALL)],
        ];
    }

    public function mediumProvider(): array
    {
        return [
            "medium 1" => [str_repeat("some string", self::SIZE_MEDIUM)],
        ];
    }

    public function largeProvider(): array
    {
        return [
            "large 1" => [str_repeat("some string", self::SIZE_LARGE)],
        ];
    }

}
