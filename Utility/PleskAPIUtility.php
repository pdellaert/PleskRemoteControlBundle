<?php
namespace Dellaert\PleskRemoteControlBundle\Utility;

class PleskAPIUtility 
{
	public static function createSubscription($pleskhost,$pleskuser,$pleskpass,$hostname,$ip,$ftplogin,$ftppass)
	{
        // setting up packet
        $packet = '<?xml version="1.0" encoding="UTF-8"?>
            <packet version="1.6.5.0">
                <webspace>
                    <add>
                        <gen_setup>
                            <name>'.$hostname.'</name>
                            <htype>vrt_hst</htype>
                            <ip_address>'.$ip.'</ip_address>
                            <status>0</status>
                        </gen_setup>
                        <hosting>
                            <vrt_hst>
                                <property>
                                    <name>ftp_login</name>
                                    <value>'.$ftplogin.'</value>
                                </property>
                                <property>
                                    <name>ftp_password</name>
                                    <value>'.$ftppass.'</value>
                                </property>
                                <ip_address>'.$ip.'</ip_address>
                            </vrt_hst>
                        </hosting>
                    </add>
                </webspace>
            </packet>';

        return PleskAPIUtility::curlAction($pleskhost,$pleskuser,$pleskpass,$packet);
	}

	private static function curlAction($pleskhost,$pleskuser,$pleskpass,$packet)
	{
		$url = 'https://'.$pleskhost.':8443/enterprise/control/agent.php';

        $headers = array(
            'HTTP_AUTH_LOGIN: '.$pleskuser,
            'HTTP_AUTH_PASSWD: '.$pleskpass,
            'Content-Type: text/xml'
        );

        $curl = curl_init();
        // do not check the name of SSL certificate of the remote server 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        // do not check up the remote server certificate
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // pass in the header elements
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // pass in the url of the target server
        curl_setopt($curl, CURLOPT_URL, $url);
        // tell CURL to return the result rather than to load it to the browser
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // pass in the packet to deliver
        curl_setopt($curl, CURLOPT_POSTFIELDS, $packet);

        // perform the CURL request and return the result 
        $reply = curl_exec($curl); 
         
        // close the CURL session
        curl_close($curl);

        return array($packet,$data);
	}
}
