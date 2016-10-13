<?php

class SharePriceChart
{
	var $stockPrice;
	
	function  __construct()
	{
		$fp = fopen("http://download.finance.yahoo.com/d/quotes.csv?s=SOLG.L&f=sl1d1t1c1ohgv&e=.csv","r");
		//this uses the fgetcsv function to store the quote info in the array $data
		$this->stockPrice = fgetcsv($fp, 1000, ",");
		fclose($fp);
	}

	function getStockPrice()
	{
		return array(
			'lastPrice'	=> $this->stockPrice[1],
			'change'	=> $this->stockPrice[4],
			'open'		=> $this->stockPrice[5],
			'high'		=> $this->stockPrice[6],
			'low'		=> $this->stockPrice[7],
			'volume'	=> $this->stockPrice[8],
		);
	}
}
