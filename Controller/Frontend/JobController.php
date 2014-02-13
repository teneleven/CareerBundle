<?php

namespace Teneleven\Bundle\CareerBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//CareerBundle
use Teneleven\Bundle\CareerBundle\Entity\Job;
use Teneleven\Bundle\CareerBundle\Entity\Reply;
use Teneleven\Bundle\CareerBundle\Form\JobType;
use Teneleven\Bundle\CareerBundle\Form\ReplyType;

//Pagerfanta 
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
     * Index Action
     * 
     * @param  Request   $request     [description]
     * @param  integer     $page         [description]
     * @param  integer     $max          [description]
     * @param  string       $template   [description]
     * 
     * @return   Response
     */
    public function indexAction(Request $request, $page = 1, $max = 5, $template = 'TenelevenCareerBundle:Frontend:index.html.twig')
    {
        $query = $this->getRepository()
            ->createQueryBuilder('j')
            ->where('j.isPublished = 1')
            ->addOrderBy('j.createdAt', 'DESC');

        $pager = new Pagerfanta(new DoctrineORMAdapter($query));

        try {
            $pager
                ->setMaxPerPage($max)
                ->setCurrentpage($request->query->get('page', $page));

        } catch (OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render(
            $template, 
            array(
                'pager' => $pager
            )
        );
    }

    /**
     * Show Action 
     * 
     * @param  [type] $slug     [description]
     * @param  string $template [description]
     * @return [type]           [description]
     */
    public function showAction($slug, $template = 'TenelevenCareerBundle:Frontend:show.html.twig')
    {
        $job = $this->getRepository()->findOneBy(array(
            'slug' => $slug, 
            'isPublished' => true
        ));

        if (!$job) {
            throw $this->createNotFoundException('That Job does not exist');
        }

        $reply = $job->createNewReply();

        $replyForm = $this->createReplyForm($reply);

        return $this->render(
            $template, 
            array(
                'job' => $job, 
                'form' => $replyForm->createView()
            )
        );
    }

    /**
     * Reply Action 
     * 
     * @param string        $slug    Slug for Job Replied To
     * @param  Request   $request [description]
     * 
     * @return [Response
     */
    public function replyAction($slug, Request $request)
    {
        $job = $this->getRepository()->findOneBy(array('slug' => $slug));   

        if (!$job) {
            throw $this->createNotFoundException('That Job does not exist');
        }

        $reply = $job->createNewReply();

        $replyForm = $this->createReplyForm($reply);

        $replyForm->handleRequest($request);

        if ($replyForm->isValid()) {

            $reply->setFile($replyForm['file']->getData());

            $root = $this->container->getParameter('kernel.root_dir');

            $reply->uploadResume($root);

            $em = $this->getDoctrine()->getManager();

            $em->persist($reply);

            $em->flush();

            $this->sendEmail($reply);

            $this->get('session')->getFlashBag()->add(
                'reply_id',
                $reply->getId()
            );

            return $this->redirect($this->generateUrl('teneleven_career_frontend_thanks', array(
                'slug' => $job->getSlug())
            ));
        }

        $this->get('session')->getFlashBag()->add(
            'error',
            'There was an error with your reply.'
        );

        return $this->render(
            'TenelevenCareerBundle:Frontend:show.html.twig', 
            array(
                'job' => $job, 
                'form' => $replyForm->createView()
            )
        );
    }

    public function thanksAction($slug)
    {
        $job = $this->getRepository()->findOneBy(array('slug' => $slug));   

        if (!$job) {
            throw $this->createNotFoundException('That Job does not exist');
        }

        if (!$this->get('session')->getFlashBag()->has('reply_id')) {
            return $this->redirect($this->generateUrl('teneleven_career_frontend_show', array(
                'slug' => $job->getSlug())
            ));
        }

        return $this->render(
            'TenelevenCareerBundle:Frontend:thanks.html.twig', 
            array(
                'job' => $job
            )
        );      
    }

    /**
     * Send a notification email to Admin
     * 
     * @param  Reply  $reply [description]
     * 
     * @return [type]        [description]
     */
    protected function sendEmail(Reply $reply)
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
                        'reply' => $reply
                    )
                )
            )
        ;

        $result = $this->get('mailer')->send($message);   
    }

    /**
     * Create a Reply Form
     * 
     * @param  [type] $reply [description]
     * 
     * @return [type]        [description]
     */
    protected function createReplyForm(Reply $reply) 
    {
        return $this->createForm(
            new ReplyType, 
            $reply, 
            array(
                'action' => $this->generateUrl(
                    'teneleven_career_frontend_reply', array(
                        'slug' => $reply->getJob()->getSlug()
                    )
                ),
                'method' => 'POST'
            )
        );        
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('TenelevenCareerBundle:Job');
    }
}
