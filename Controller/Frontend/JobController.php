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
     * Lists all Job entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('TenelevenCareerBundle:Job')->findAll();

        return $this->render('TenelevenCareerBundle:Frontend:index.html.twig', array(
            'jobs' => $jobs,
        ));
    }

    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('TenelevenCareerBundle:Job')->findOneBy(array('slug' => $slug));

        $replyForm = $this->createForm(new ReplyType(), new Reply(),array(
            'action' => $this->generateUrl('teneleven_sandbox_careers_reply', array('slug' => $job->getSlug()))
        ));

        return $this->render('TenelevenCareerBundle:Frontend:show.html.twig', array('job' => $job, 'form' => $replyForm->createView()));
    }

    public function replyAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('TenelevenCareerBundle:Job')->findOneBy(array('slug' => $slug));   

        $reply = new Reply();

        $reply->setJob($job);
        
        $replyForm = $this->createForm(new ReplyType(), $reply, array(
            'action' => $this->generateUrl('teneleven_sandbox_careers_reply', array(
                'slug' => $job->getSlug()
            ))
        ));     

        $replyForm->handleRequest($request);

        if ($replyForm->isValid()) {

            $reply->setFile($replyForm['file']->getData());

            $reply->uploadResume();

            $em->persist($reply);

            $em->flush();
        }

        return $this->redirect($this->generateUrl('teneleven_sandbox_careers_show', array('slug' => $job->getSlug())));
    }
}
