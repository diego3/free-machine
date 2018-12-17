<?php 


class CURL {
	
	//"http://localhost:5000/alunos?orderBy=name&origin=DESC"

	public static function request($url, $port, $method = 'GET', $fields = null, $headers = []){
		$curl = curl_init();
		$config = [
		  CURLOPT_PORT => $port,
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $method,
		];

		if(!empty($headers)) {
			$config[CURLOPT_HTTPHEADER] = $headers;
		}

		if($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
			if( !empty($fields)) {
				$config[CURLOPT_POSTFIELDS] = $fields;
			}
		}

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