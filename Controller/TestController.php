<?php
namespace Dellaert\PleskRemoteControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dellaert\PleskRemoteControlBundle\Utility\PleskAPIUtility;

class TestController extends Controller 
{
    public function createSubscriptionAction($pleskhost,$pleskuser,$pleskpass,$hostname,$ip,$ftplogin,$ftppass)
    {
        $result = PleskAPIUtility::createSubscription($pleskhost,$pleskuser,$pleskpass,$hostname,$ip,$ftplogin,$ftppass);

        return $this->render('DellaertPleskRemoteControlBundle::debug.html.twig',array('packet'=>$result['packet'],'result'=>$result['reply']));
    }
}
