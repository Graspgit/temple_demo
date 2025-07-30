<?php

namespace App\Models;

use CodeIgniter\Model;

class Common_model extends Model
{
    // No $table property since this model does not use any table

    public function postJson($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //skip cert check
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //skip host check
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
	public function getJson($url, $data)
    {

		// Build the query string and append it to the URL
		$fullUrl = $url . '?' . http_build_query($data);
        $ch = curl_init($fullUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //skip cert check
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //skip host check
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
