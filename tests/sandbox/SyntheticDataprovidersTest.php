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
    public function testSmall (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     */
    public function testMedium (string $text): void
    {
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     */
    public function testLarge (string $text): void
    {
        $this->assertIsString($text);
    }

    public function testLast(): void
    {
        $this->assertTrue(true);
    }

    public function smallProvider(): \Traversable
    {
        yield "small 1" => [str_repeat("some string", self::SIZE_SMALL)];
        yield "small 2" => [str_repeat("some string", self::SIZE_SMALL)];
        yield "small 3" => [str_repeat("some string", self::SIZE_SMALL)];
    }

    public function mediumProvider(): array
    {
        return [
            "medium 1" => [str_repeat("some string", self::SIZE_MEDIUM)],
            "medium 2" => [str_repeat("some string", self::SIZE_MEDIUM)],
            "medium 3" => [str_repeat("some string", self::SIZE_MEDIUM)],
            "medium 4" => [str_repeat("some string", self::SIZE_MEDIUM)],
            "medium 5" => [str_repeat("some string", self::SIZE_MEDIUM)],
        ];
    }

    public function largeProvider(): array
    {
        return [
            "large 1" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 2" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 3" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 4" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 5" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 6" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 7" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 8" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 9" => [str_repeat("some string", self::SIZE_LARGE)],
            "large 10" => [str_repeat("some string", self::SIZE_LARGE)],
        ];
    }

}
