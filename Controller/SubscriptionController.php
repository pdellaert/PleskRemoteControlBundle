<?php
namespace Dellaert\PleskRemoteControl\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class Subscription extends Controller {
    public function createAction($pleskhost,$pleskuser,$pleskpass,$hostname,$ip,$ftplogin,$ftppass){
        $url = 'https://'.$pleskhost.':8443/enterprise/control/agent.php';

        $headers = array(
            'HTTP_AUTH_LOGIN: '.$pleskuser),
            'HTTP_AUTH_PASSWD: '.$pleskpass),
            'Content-Type: text/xml'
        );

        $curl = curl_init();
        // do not check the name of SSL certificate of the remote server 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // do not check up the remote server certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // pass in the header elements
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // pass in the url of the target server
        curl_setopt($ch, CURLOPT_URL, $url);
        // tell CURL to return the result rather than to load it to the browser
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

        // perform the CURL request and return the result 
        $data = curl_exec($ch); 
         
        // close the CURL session
        curl_close($ch); 

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
