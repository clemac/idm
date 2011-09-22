<?php
# My Is.Gd Class PHP.
# author: BobCaTT
# url: http://www.menfin.net
# version: 1.0
# GPL v3

#**********************************************************************
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# ( at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Online: http://www.gnu.org/licenses/gpl.txt
#**********************************************************************

if (!defined("_ECRIRE_INC_VERSION")) return;

class MyIsGd
{
		
	public  $httpCode;
	public	$lastUrl;
	public	$lastShortenUrl;
	public	$httpTimeout = 15;

	private $ProxyIp;
	private $ProxyPort;
	private $proxyType = CURLPROXY_HTTP;
	private $ProxyUsername;
	private $ProxyPass;

	private $useProxy = false;
	private $useProxyAuth = false;
	
	// via is.gd api documentation
	private $MaxLength = 2000;
	private	$UrlIsGd = 'http://is.gd/create.php?format=json&url=';
	
	function __construct ($url='', $proxyip = '', $proxyport = 0, $proxyuser = '', $proxypass = '')
	{
		$this->httpCode = 0;
		$this->lastUrl = '';
		$this->lastShortenUrl = '';

		if ( strlen($proxyip) > 0 && $proxyport > 0 )
		{
			$this->ProxyIp = $proxyip;
			$this->ProxyPort = $proxyport;
			$this->useProxy = true;
		}
		if ( strlen($proxyuser) > 0 && strlen($proxypass) > 0)
		{
			$this->ProxyUsername = $proxyuser;
			$this->ProxyPassword = $proxypass;
			$this->useProxyAuth = true;
		}

		if ( strlen($url) > 0 ) $this->shortenUrl($url);
	}

/*
 * url: the url that will be executed by cUrl
 * methodget : Get or Post method
 * returntransfert : Return the server page
 * return : returns the cUrl object, ready to be executed
 */
	private function curlSetOpts($url, $methodget, $returntransfert){
		if ( $url == "" ) return null;
		if ( ! function_exists('curl_init') ) return null;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->httpTimeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returntransfert);	

		if ( $methodget == true ) curl_setopt($ch, CURLOPT_HTTPGET, true);
		else curl_setopt($ch, CURLOPT_POST, true);

		if ( $this->useProxy == true )
		{
			curl_setopt($ch, CURLOPT_PROXY, "$this->ProxyIp:$this->ProxyPort");
			curl_setopt($ch, CURLOPT_PROXYTYPE, $this->ProxyType);
		}
		if ( $this->useProxyAuth == true )
		{
			curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$this->ProxyUsername:$this->ProxyPassword");
		}

		return $ch;
	}

/*
 * url : url to be shortened
 * return : true is the command has been executed
 *	    false is there was an error in the parameter
 */
	public function shortenUrl ($url)
	{
		$this->httpCode = 0;
		if ( strlen($url) <= 0 || strlen($url) > 2000 )
		{
			return false;
		}

		$request = $this->UrlIsGd . rawurlencode($url);
		if(function_exists(curl_init))
		{	
			$ch = $this->curlSetOpts($request, true, true);

			$response = curl_exec($ch);
			$Headers = curl_getinfo($ch);

			curl_close($ch);

			$this->httpCode = $Headers['http_code'];

			if($Headers['http_code'] == 200)
			{
				$this->lastUrl = $url;
				$content = json_decode($response);
				$this->lastShortenUrl = $content->shorturl;
			}
		}
		return true; // command has been executed
	}
}//end My Is.Gd  Class

?>
