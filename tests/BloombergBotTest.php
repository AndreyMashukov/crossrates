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
 * Tests for bloomberg.com Bot
 *
 * @author  Andrey Mashukov <a.mashukoff@gmail.com>
 *
 * @runTestsInSeparateProcesses
 */

class BloombergBotTest extends TestCase
    {

	use InternalWebServer;

	/**
	 * Name folder which should be removed after tests
	 *
	 * @var string
	 */
	protected $remotepath;

	/**
	 * Testing host
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * Should return valid HTML block
	 *
	 * @return void
	 */

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */

	protected function setUp()
	    {
		$this->remotepath = $this->webserverURL();
		$this->host       = $this->remotepath . "/HTTPResponder.php";
	    } //end setUp()


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */

	protected function tearDown()
	    {
		unset($this->remotepath);
	    } //end tearDown()


	/**
	 * Should get currencies rates
	 * Example URL: https://www.bloomberg.com/markets/api/security/currency/cross-rates/USD,RUB
	 *
	 * @return void
	 */

	public function testShouldGetCurrenciesRates()
	    {
		$this->host = $this->remotepath . "/HTTPResponder.php";
		define("BLOOMBERG_URL", $this->host);

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

		$this->assertEquals($expected, $crossrates);
	    } //end testShouldGetCurrenciesRates


	/**
	 * Should not allow make request with invalid currencies names
	 *
	 * @return void
	 *
	 * @exceptioncode EXCEPTION_INVALID_REQUEST_RESULT
	 */

	public function testShouldNotAllowMakeRequestWithInvalidCurrenciesNames()
	    {
		$this->host = $this->remotepath . "/HTTPResponderInvalid.php";

		define("BLOOMBERG_URL", $this->host);
		define("EXCEPTION_INVALID_REQUEST_RESULT", 1);
		$bot        = new BloombergBot();
		$this->expectException(Exception::class);
		$this->expectExceptionCode(EXCEPTION_INVALID_REQUEST_RESULT);
		$crossrates = $bot->get("USD", "UB");
	    } //end testShouldNotAllowMakeRequestWithInvalidCurrenciesNames()


	/**
	 * Should not allow make request without defined bloomberg url
	 *
	 * @return void
	 *
	 * @exceptioncode EXCEPTION_BLOOMBERG_URL_WAS_NOT_SET
	 */

	public function testShouldNotAllowMakeRequestWithoutDefinedBloombergUrl()
	    {
		$this->host = $this->remotepath . "/HTTPResponder.php";

		define("EXCEPTION_BLOOMBERG_URL_WAS_NOT_SET", 1);
		$bot        = new BloombergBot();
		$this->expectException(Exception::class);
		$this->expectExceptionCode(EXCEPTION_BLOOMBERG_URL_WAS_NOT_SET);
		$crossrates = $bot->get("USD", "RUB");
	    } //end testShouldNotAllowMakeRequestWithoutDefinedBloombergUrl()


    } //end class




?>