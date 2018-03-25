<?php

/**
 * PHP version 7.1
 *
 * @package AM\Crossrates
 */

namespace Test;

use \Exception;
use \AM\Crossrates\BloombergBot;
use \Logics\Tests\InternalWebServer;
use \PHPUnit\Framework\TestCase;

/**
 * Integration tests for bloomberg.com Bot
 *
 * @author  Andrey Mashukov <a.mashukoff@gmail.com>
 */

class BloombergBotIntegrtation extends TestCase
    {

	/**
	 * Should get currencies rates
	 * Example URL: https://www.bloomberg.com/markets/api/security/currency/cross-rates/USD,RUB
	 *
	 * @return void
	 */

	public function testShouldGetCurrenciesRates()
	    {
		define("BLOOMBERG_URL", "https://www.bloomberg.com");

		$bot        = new BloombergBot();
		$crossrates = $bot->get("USD", "RUB");

		$expected = [
		    "srtaight" => array(
				   "pair"  => "USD_RUB",
				   "value" => 59.656,
				  ),
		    "inverted" => array(
				   "pair"  => "RUB_USD",
				   "value" => 0.01676,
    				  ),
		];

		$this->assertEquals($expected["srtaight"]["pair"], $crossrates["srtaight"]["pair"]);
		$this->assertEquals($expected["inverted"]["pair"], $crossrates["inverted"]["pair"]);
		$this->assertTrue($crossrates["srtaight"]["value"] > 0);
		$this->assertTrue($crossrates["inverted"]["value"] > 0);
	    } //end testShouldGetCurrenciesRates


    } //end class


?>
