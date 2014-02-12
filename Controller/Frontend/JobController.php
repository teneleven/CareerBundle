<?php

namespace Teneleven\Bundle\CareerBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Teneleven\Bundle\CareerBundle\Entity\Job;
use Teneleven\Bundle\CareerBundle\Entity\Reply;
use Teneleven\Bundle\CareerBundle\Form\JobType;
use Teneleven\Bundle\CareerBundle\Form\ReplyType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;

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
    public function indexAction(Request $request)
    {
        return $this->render('TenelevenCareerBundle:Frontend:index.html.twig');
    }

    /**
     * List all Jobs
     */
    public function listAction(Request $request, $page = 1, $template = 'TenelevenCareerBundle:Frontend:list.html.twig')
    {
        $query = $this->getRepository()
            ->createQueryBuilder('j')
            ->where('j.isPublished = 1')
            ->addOrderBy('j.releaseDate', 'DESC')
            ->getQuery()
        ;

        $pager = new Pagerfanta(new DoctrineORMAdapter($query));

        try {
            $pager->setCurrentpage($page);
        } catch(OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render($template, array('jobs' => $pager));
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

            $root = $this->container->getParameter('kernel.root_dir');

            $reply->uploadResume($root);

            $em->persist($reply);

            $em->flush();

            $this->sendEmail($replyForm->getData());

            return $this->redirect($this->generateUrl('teneleven_career_frontend_thanks', array(
                'slug' => $job->getSlug())
            ));

        }

        $this->get('session')->getFlashBag()->add(
            'error',
            'There was an error with your reply.'
        );

        return $this->render('TenelevenCareerBundle:Frontend:show.html.twig', array(
            'job' => $job, 
            'form' => $replyForm->createView()
        ));
    }

    protected function sendEmail($values)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->container->getParameter('teneleven_career.subject'))
            ->setFrom($this->container->getParameter('teneleven_career.from'))
            ->setTo($this->container->getParameter('teneleven_career.to'))
            ->setContentType($this->container->getParameter('teneleven_career.content_type'))
            ->setBody(
                $this->renderView(
                    $this->container->getParameter('teneleven_career.template'),
                    array(
                        'values' => $values
                    )
                )
            )
        ;

        $result = $this->get('mailer')->send($message);   
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('TenelevenCareerBundle:Job');
    }
}
