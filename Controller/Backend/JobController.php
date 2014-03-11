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
use Pagerfanta\Adapter\DoctrineCollectionAdapter;

/**
 * Job controller.
 *
 */
class JobController extends Controller
{

    /**
     * Lists all Jobs.
     */
    public function indexAction(Request $request, $page = 1, $max = 20)
    {
        $jobs = $this->getRepository()->findAll();

        $pager = new Pagerfanta(new ArrayAdapter($jobs));

        try {
            $pager
                ->setMaxPerPage($max)
                ->setCurrentpage($request->query->get('page', $page));

        } catch (OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render('TenelevenCareerBundle:Backend:index.html.twig', array(
            'pager' => $pager
        ));
    }
    
    /**
     * Creates a new Job entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Job();

        $form = $this->createCreateForm($entity);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($entity);
            
            $em->flush();

            return $this->redirect($this->generateUrl('teneleven_career_backend_job_show', array('id' => $entity->getId())));
        }

        return $this->render('TenelevenCareerBundle:Backend:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Job entity.
    *
    * @param Job $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('teneleven_career_backend_job_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Job entity.
     *
     */
    public function newAction()
    {
        $entity = new Job();
        $form   = $this->createCreateForm($entity);

        return $this->render('TenelevenCareerBundle:Backend:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Job entity.
     *
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $job = $em->getRepository('TenelevenCareerBundle:Job')->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $pager = new Pagerfanta(new DoctrineCollectionAdapter($job->getReplies()));

        try {
            $pager->setCurrentpage($request->query->get('page', 1));

        } catch (OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render(
            'TenelevenCareerBundle:Backend:show.html.twig', 
            array(
                'job' => $job,
                'delete_form' => $deleteForm->createView(),
                'pager' => $pager
            )
        );
    }

    /**
     * Displays a form to edit an existing Job entity.
     *
     */
    public function editAction($id)
    {
        $job = $this->getDoctrine()->getManager()->getRepository('TenelevenCareerBundle:Job')->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $editForm = $this->createEditForm($job);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TenelevenCareerBundle:Backend:edit.html.twig', array(
            'job'      => $job,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Job entity.
    *
    * @param Job $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('teneleven_career_backend_job_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Job entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $job = $this->getRepository()->find($id);

        if (!$job) {
            throw $this->createNotFoundException('Unable to find Job.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($job);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('teneleven_career_backend_job_edit', array('id' => $id)));
        }

        return $this->render('TenelevenCareerBundle:Backend:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Job entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('teneleven_career_backend_job'));
    }

    /**
     * Creates a form to delete a Job entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('teneleven_career_backend_job_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('TenelevenCareerBundle:Job');
    }
}
