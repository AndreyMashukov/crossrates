<?php

/**
 * PHP version 7.1
 *
 * @package AM\Crossrates
 */

namespace AM\Crossrates;

use \Exception;
use \Logics\Foundation\HTTP\HTTPclient;

/**
 * Bloomberg.com currencies bot
 *
 * @author  Andrey Mashukov <a.mashukoff@gmail.com>
 */

class BloombergBot
    {

	/**
	 * Get currencies rates
	 *
	 * @param string $firstcurrency  First  currency
	 * @param string $secondcurrency Second currency
	 *
	 * @return array result of query
	 *
	 * @throws Exception Bloomberg URL wasn't set
         *
	 * @exceptioncode EXCEPTION_BLOOMBERG_URL_WAS_NOT_SET
	 */

	public function get(string $firstcurrency, string $secondcurrency):array
	    {
		if (defined("BLOOMBERG_URL") === false)
		    {
			throw new Exception("Bloomberg URL wasn't set", EXCEPTION_BLOOMBERG_URL_WAS_NOT_SET);
		    } //end if

		$http   = new HTTPclient(BLOOMBERG_URL . "/markets/api/security/currency/cross-rates/" .
		    $firstcurrency . "," . $secondcurrency . "");

		$result = json_decode($http->get(), true);
		$this->_validate($result);

		$rates = [
		    "srtaight" => array(
				   "pair"  => $firstcurrency . "_" . $secondcurrency,
				   "value" => $result["data"][$firstcurrency][$secondcurrency],
				  ),
		    "inverted" => array(
				   "pair"  => $secondcurrency . "_" . $firstcurrency,
				   "value" => $result["data"][$secondcurrency][$firstcurrency],
				  ),
		];

		return $rates;
	    } //end get()


	/**
	 * Validate query result
	 *
	 * @param array $result Result of HTTP query
	 *
	 * @return void
	 */

	private function _validate(array $result)
	    {
		if (isset($result["exceptions"]) === true)
		    {
			throw new Exception("Invalid request result", EXCEPTION_INVALID_REQUEST_RESULT);
		    } //end if

	    } //end _validate()


    } //end class

?>
