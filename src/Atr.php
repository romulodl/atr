<?php

namespace Romulodl;

class Atr
{
	/**
	 * Calculate the ATR based on this formula
	 * true range: max(H - L, abs(H - PC), abs(PC - L))
	 * average true range: [Prev ATR * (Period - 1) + TR] / Period
	 *
	 * $hlc_values array of 3 values: [high, low, close]
	 * $period int
	 */
	public function calculate(array $hlc_values, int $period = 14) : float
	{
		if (empty($hlc_values) || count($hlc_values) < $period) {
			throw new \Exception('[' . __METHOD__ . '] $values parameters is invalid');
		}

		$true_range = [];
		$prev_close = false;
		$prev_atr = false;
		foreach ($hlc_values as $key => $value) {
			if (!$this->isValidHLCValue($value)) {
				throw new \Exception('[' . __METHOD__ . '] invalid HLC value');
			}

			if (empty($true_range)) {
				$prev_close = $value[2];
				$true_range[] = $value[0] - $value[1];
				continue;
			}

			if (count($true_range) < $period) {
				$true_range[] = max([
					$value[0] - $value[1],
					abs($value[0] - $prev_close),
					abs($prev_close - $value[1])
				]);

				$prev_close = $value[2];
				if (count($true_range) === $period) {
					$prev_atr = array_sum($true_range) / count($true_range);
				}

				continue;
			}

			$tr = max([
				$value[0] - $value[1],
				abs($value[0] - $prev_close),
				abs($prev_close - $value[1])
			]);
			$prev_close = $value[2];

			$prev_atr = ($prev_atr * ($period - 1) + $tr) / $period;
		}

		return $prev_atr;
	}

	private function isValidHLCValue($values) : bool
	{
		return is_array($values) &&
			count($values) === 3 &&
			is_numeric($values[0]) &&
			is_numeric($values[1]) &&
			is_numeric($values[2]);
	}
}
