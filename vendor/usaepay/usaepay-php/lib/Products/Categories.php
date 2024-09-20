<?php
namespace USAePay\Products;
use \USAePay\API as API;
use \USAePay\Exception\CurlException as CurlException;
use \USAePay\Exception\SDKException as SDKException;
use \USAePay\Exception\ueException as ueException;


class Categories{

	public static function get($Data=array()){
		$Response_type="json";
		$Path="/products/categories";
		$Params=[];

		if(array_key_exists('limit',$Data)) $Params['limit']=$Data['limit'];
		if(array_key_exists('offset',$Data)) $Params['offset']=$Data['offset'];

		if(array_key_exists("category_key",$Data)){
			$Path.='/'.$Data["category_key"];
			unset($Data["category_key"]);
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

	public static function post($Data=array()){
		$Response_type="json";
		$Path="/products/categories";
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

	public static function delete($Data=array()){
			if(!array_key_exists("category_key",$Data)) throw new SDKexception("Categories delete requires category_key");

		$Response_type="json";
		$Path="/products/categories";
		$Params=[];

		if(array_key_exists("category_key",$Data)){
			$Path.='/'.$Data["category_key"];
			unset($Data["category_key"]);
		}

		try{
			return API::runCall('delete',$Path,$Data,$Params,$Response_type);
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

	public static function put($Data=array()){
			if(!array_key_exists("category_key",$Data)) throw new SDKexception("Categories put requires category_key");

		$Response_type="json";
		$Path="/products/categories";
		$Params=[];

		if(array_key_exists("category_key",$Data)){
			$Path.='/'.$Data["category_key"];
			unset($Data["category_key"]);
		}

		try{
			return API::runCall('put',$Path,$Data,$Params,$Response_type);
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