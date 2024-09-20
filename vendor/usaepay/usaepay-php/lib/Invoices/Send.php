<?php
namespace USAePay\Invoices;
use \USAePay\API as API;
use \USAePay\Exception\CurlException as CurlException;
use \USAePay\Exception\SDKException as SDKException;
use \USAePay\Exception\ueException as ueException;


class Send{

	public static function post($Data=array()){
		if(!array_key_exists("invoice_key",$Data)) throw new SDKexception("Send post requires invoice_key");

		$invoice_key=$Data["invoice_key"];
		unset($Data["invoice_key"]);

		$Response_type="json";
		$Path="/invoices/$invoice_key/send";
		$Params=[];

		try{
			return API::runCall('post',$Path,$Data,$Params,$Response_type);
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