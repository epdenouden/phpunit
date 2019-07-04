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
        print "run " . __METHOD__ . "\n";
        $this->assertTrue(true);
    }

    /**
     * @dataProvider smallProvider
     * @group 3736_small
     * @group 3736_provider
     */
    public function testSmallProvider(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumProvider
     * @group 3736_small
     * @group 3736_provider
     */
    public function testMediumProvider(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeProvider
     * @group 3736_large
     * @group 3736_provider
     */
    public function testLargeProvider(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider smallGenerator
     * @group 3736_small
     * @group 3736_generator
     *
     */
    public function testSmallGenerator(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider mediumGenerator
     * @group 3736_medium
     * @group 3736_generator
     */
    public function testMediumGenerator(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider largeGenerator
     * @group 3736_large
     * @group 3736_generator
     */
    public function testLargeGenerator(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    /**
     * @dataProvider smallProvider
     * @dataProvider smallGenerator
     * @group 3736_provider
     * @group 3736_generator
     * @group 3736_mixed
     */
    public function testMixedProviderAndGenerator(string $text): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertIsString($text);
    }

    public function testLast(): void
    {
        print "run " . __METHOD__ . "\n";
        $this->assertTrue(true);
    }

    public function smallProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 3; $i++) {
            print "add small $i\n";
            $data["provider small $i"] = [\str_repeat('some string', self::SIZE_SMALL)];
        }
        return $data;
    }

    public function mediumProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 5; $i++) {
            print "yield medium $i\n";
            $data["provider medium $i"] = [\str_repeat('some string', self::SIZE_MEDIUM)];
        }
        return $data;
    }

    public function largeProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            print "yield large $i\n";
            $data["provider large $i"] = [\str_repeat('some string', self::SIZE_LARGE)];
        }
        return $data;
    }

    public function smallGenerator(): \Generator
    {
        for ($i = 0; $i < 3; $i++) {
            print "yield small $i\n";
            yield "generator small $i" => [\str_repeat('some string', self::SIZE_SMALL)];
        }
    }

    public function mediumGenerator(): \Generator
    {
        for ($i = 0; $i < 5; $i++) {
            print "yield medium $i\n";
            yield "generator medium $i" => [\str_repeat('some string', self::SIZE_MEDIUM)];
        }
    }

    public function largeGenerator(): \Generator
    {
        for ($i = 0; $i < 10; $i++) {
            print "yield large $i\n";
            yield "generator large $i" => [\str_repeat('some string', self::SIZE_LARGE)];
        }
    }
}
