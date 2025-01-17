<?php

namespace CoderDojo\WebsiteBundle\Controller;

use CoderDojo\WebsiteBundle\Entity\Dojo;
use CoderDojo\WebsiteBundle\Form\Type\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PagesController extends Controller
{
    /******************
     * MEEHELPEN
     ******************/

    /**
     * @Route("/meehelpen/opstarten", name="setup")
     */
    public function setupAction()
    {
        return $this->render(':Pages:Meehelpen/setup.html.twig');
    }

    /**
     * @Route("/meehelpen/mentor-worden", name="mentors")
     */
    public function mentorAction()
    {
        return $this->render(':Pages:Meehelpen/mentor.html.twig');
    }

    /**
     * @Route("/meehelpen/materiaal", name="material")
     */
    public function materialAction()
    {
        return $this->render(':Pages:Meehelpen/material.html.twig');
    }

    /******************
     * Samenwerkingen
     ******************/
    /**
     * @Route("/codeweek", name="codeweek")
     */
    public function euCodeWeekAction()
    {
        $events = $this->getDoctrine()->getRepository("CoderDojoWebsiteBundle:DojoEvent")->getCodeWeek2020Events();
        return $this->render(':Pages:codeweek.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/online", name="online")
     */
    public function onlineAction()
    {
        $events = $this->getDoctrine()->getRepository("CoderDojoWebsiteBundle:DojoEvent")->getOnlineEvents();
        return $this->render(':Pages:online_dojo.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/weekvandemediawijsheid", name="weekvandemediawijsheid")
     */
    public function weekvandemediawijsheidAction()
    {
        $events = $this->getDoctrine()->getRepository("CoderDojoWebsiteBundle:DojoEvent")->getWVDMWHEvents();
        return $this->render(':Pages:mediawijsheidweek.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/dojocon", name="dojocon")
     */
    public function dojoConAction()
    {
        return $this->render(':Pages:dojocon.html.twig');
    }

    /**
     * @Route("/dojocon2021", name="dojocon2021")
     */
    public function dojo2021ConAction()
    {
        return $this->render(':Pages:dojocon2021.html.twig');
    }

    /**
     * @Route("/dojocon2019", name="dojocon2019")
     */
    public function dojoCon2019Action()
    {
        return $this->render(':Pages:dojocon2019.html.twig');
    }

    /******************
     * De Stichting
     ******************/

    /**
     * @Route("/informatie/over-coderdojo", name="about")
     */
    public function aboutAction()
    {
        return $this->render(':Pages:Achtergrond/about.html.twig');
    }

    /**
     * @Route("/informatie/over-de-stichting", name="foundation")
     */
    public function foundationAction()
    {
        return $this->render(':Pages:Achtergrond/stichting.html.twig');
    }

    /**
     * @Route("/informatie/nieuwsbrief", name="newsletter")
     */
    public function newsletterAction()
    {
        return $this->render(':Pages:newsletter.html.twig');
    }

    /******************
     * SUPPORTING
     ******************/

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction()
    {
        return $this->render(':Pages:privacy.html.twig');
    }

    /**
     * @Route("/upgrade-dojo", name="upgrade-dojo")
     */
    public function upgradeDojoAction()
    {
        return $this->render(':Pages:upgrade-dojo.html.twig');
    }
}
