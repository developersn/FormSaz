<?php
class sn
{
	public $API = '';
	
	private $go = array();
	
	public function request($amount = NULL , $order_id = NULL , $callback = NULL)
	{
		// Security
		@session_start();
		$sec = uniqid();
		$md = md5($sec.'vm');
		// Security
		
		
							$data_string = json_encode(array(
							'pin'=> $this->API,
							'price'=> $amount,
							'callback'=> $callback.'&md='.$md.'&sec='.$sec ,
							'order_id'=> $order_id,
							'ip'=> $_SERVER['REMOTE_ADDR'],
							'callback_type'=>2
							));

							$ch = curl_init('https://developerapi.net/api/v1/request');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen($data_string))
							);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
							$result = curl_exec($ch);
							curl_close($ch);
							$json = json_decode($result,true);
		
		if($json['result']!=1)
		{
			
			print_r($json['msg']);
			die;
		}
		else
		{
				// Set Session
				$_SESSION[$sec] = [
					'price'=>$amount ,
					'order_id'=>$invoice_id ,
					'au'=>$json['au'] ,
				];
			$this->go[$json['au']] = "please w8...<div style='display:none'>".$json['form']."<script language='javascript'>document.payment.submit()</script>";
			return $json['au'];
		}
	}
	
	public $SaleReferenceId = '';
	public function verify($price = NULL ,$order_id = NULL , $au = NULL)
	{
		
					
					$bank_return = $_POST + $_GET ;
					$data_string = json_encode(array (
					'pin' => $this->API,
					'price' => $price,
					'order_id' => $order_id,
					'au' => $au,
					'bank_return' =>$bank_return,
					));

					$ch = curl_init('https://developerapi.net/api/v1/verify');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string))
					);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
					$result = curl_exec($ch);
					curl_close($ch);
					$json = json_decode($result,true);
			
			if( ! empty($json['result']) and $json['result']==1)
				return true;
			return false;
		
	}
	public function go2bank($id='')
	{
		die($this->go[$id]);
	}
}