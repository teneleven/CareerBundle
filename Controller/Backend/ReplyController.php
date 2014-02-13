<?php

namespace Teneleven\Bundle\CareerBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Teneleven\Bundle\CareerBundle\Entity\Job;
use Teneleven\Bundle\CareerBundle\Form\JobType;

//Pagerfanta 
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;

/**
 * Reply controller.
 *
 */
class ReplyController extends Controller
{
    /**
     * Deletes a Reply From Job
     *
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();

            $reply = $em->getRepository('TenelevenCareerBundle:Reply')->find($id);

            if (!$reply) {
                throw $this->createNotFoundException('Unable to Find Reply');
            }

            $job = $reply->getJob();

            $em->remove($reply);
            $em->flush();
        return $this->redirect($this->generateUrl('teneleven_career_backend_job_show', array('id' => $job->getId())));
    }
}