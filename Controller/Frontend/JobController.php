<?php

namespace Teneleven\Bundle\CareerBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Teneleven\Bundle\CareerBundle\Entity\Job;
use Teneleven\Bundle\CareerBundle\Entity\Reply;
use Teneleven\Bundle\CareerBundle\Form\JobType;
use Teneleven\Bundle\CareerBundle\Form\ReplyType;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Job controller.
 *
 */
class JobController extends Controller
{

    /**
     * Display Index Template
     *
     */
    public function indexAction()
    {
        return $this->render('TenelevenCareerBundle:Frontend:index.html.twig');
    }

    /**
     * List all Jobs
     */
    public function listAction($template = 'TenelevenCareerBundle:Frontend:list.html.twig')
    {
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('TenelevenCareerBundle:Job')->findBy(array(
            'isPublished' => true
        ));

        return $this->render($template, array(
            'jobs' => $jobs,
        ));
    }

    /**
     * Show Individual Job
     */
    public function showAction($slug, $template = 'TenelevenCareerBundle:Frontend:show.html.twig')
    {
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('TenelevenCareerBundle:Job')->findOneBy(array('slug' => $slug, 'isPublished' => true));

        $replyForm = $this->createForm(new ReplyType(), new Reply(),array(
            'action' => $this->generateUrl('teneleven_career_frontend_reply', array('slug' => $job->getSlug()))
        ));

        return $this->render($template, array(
            'job' => $job, 
            'form' => $replyForm->createView()
        ));
    }

    /**
     * Handle Replies for Job
     */
    public function replyAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('TenelevenCareerBundle:Job')->findOneBy(array('slug' => $slug));   

        $reply = new Reply();

        $reply->setJob($job);
        
        $replyForm = $this->createForm(new ReplyType(), $reply, array(
            'action' => $this->generateUrl('teneleven_career_frontend_reply', array(
                'slug' => $job->getSlug()
            )),
            'method' => 'POST'
        ));     

        $replyForm->handleRequest($request);

        if ($replyForm->isValid()) {

            $reply->setFile($replyForm['file']->getData());

            $reply->uploadResume();

            $em->persist($reply);

            $em->flush();
        }

        return $this->redirect($this->generateUrl('teneleven_career_frontend_show', array('slug' => $job->getSlug())));
    }
}
