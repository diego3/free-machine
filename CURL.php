<?php 


class CURL {
	
	//"http://localhost:5000/alunos?orderBy=name&origin=DESC"

	public static function request($url, $port){
		$curl = curl_init();
		$config = [
		  CURLOPT_PORT => $port,
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => [

		  ],
		];

		curl_setopt_array($curl, $config);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  throw new CURLException("cURL Error #:" . $err, 1);
		} 

		return $response;
	}
}

class CURLException extends Exception {}