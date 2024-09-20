<?php
namespace USAePay\Transactions;
use \USAePay\API as API;
use \USAePay\Exception\CurlException as CurlException;
use \USAePay\Exception\SDKException as SDKException;
use \USAePay\Exception\ueException as ueException;


class Receipts{

	public static function get($Data=array()){
		if(!array_key_exists('receipt_key',$Data)) $Data['receipt_key']='vterm';

		if(!array_key_exists("trankey",$Data)) throw new SDKexception("Receipts get requires trankey");

		$trankey=$Data["trankey"];
		unset($Data["trankey"]);

		$Response_type="base64";
		$Path="/transactions/$trankey/receipts";
		$Params=[];

		if(array_key_exists("receipt_key",$Data)){
			$Path.='/'.$Data["receipt_key"];
			unset($Data["receipt_key"]);
		}

		try{
			return API::runCall('get',$Path,$Data,$Params,$Response_type);
		}
		catch(CurlException $e){
			throw $e;
		}
		catch(SDKException $e){
			throw $e;
		}
		catch(ueException $e){
			throw $e;
		}
		catch(\Exception $e){
			throw new SDKException("Unexpected exception thrown");
		}
	}
}
?>