<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Romulodl\Atr;

final class AtrTest extends TestCase
{
	public function testCalculateAtrWithWrongTypeValues(): void
	{
		$this->expectException(Exception::class);

		$atr = new Atr();
		$atr->calculate(['poop']);
	}

	public function testCalculateAtrWithEmptyValues(): void
	{
		$this->expectException(Exception::class);

		$atr = new Atr();
		$atr->calculate([]);
	}

	public function testCalculateAtrWithInvalidValues(): void
	{
		$values = [
			[9310.73, 1000, 2000],
			[1000, 2000],
		];

		$this->expectException(Exception::class);

		$atr = new Atr();
		$atr->calculate($values);
	}

	public function testCalculateAtrWithStringValues(): void
	{
		$values = [
			[9310.73, 1000, 2000],
			[9310.73, 'poop', 2000],
		];

		$this->expectException(Exception::class);

		$atr = new Atr();
		$atr->calculate($values);
	}

	public function testCalculateAtrWithValid14Values(): void
	{
		$values = require(__DIR__ . '/values.php');
		$values = array_slice($values, -14);

		$atr = new Atr();
		$this->assertSame(494.21, round($atr->calculate($values), 2));
	}

	public function testCalculateAtrWithAllValidValues(): void
	{
		$values = require(__DIR__ . '/values.php');

		$atr = new Atr();
		$this->assertSame(510.18, round($atr->calculate($values), 2));
	}
}
