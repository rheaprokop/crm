<?php

/*
 * This application belongs to Rhea Software (rheasoftware.com)
 * Illegal distribution is prohibited and punishable by law.  * 
 */

namespace CRM\SupportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use CRM\SupportBundle\Entity\Ticket;
use CRM\SupportBundle\Entity\TicketReply;
use CRM\SupportBundle\Form\TicketReplyType;
use CRM\SupportBundle\Form\TicketType;
use CRM\UserBundle\Entity\UserActivity;
use CRM\UserBundle\Form\UserActivityType;

class TicketController extends Controller {

    public function indexAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();
        $categories = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:TicketCategory', 'b')
                ->getQuery()
                ->getResult();

        $datetime = new \DateTime("now");
        $d = $datetime->format('ymdh');

        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(new TicketType(), $ticket);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {

                try {
                    $em = $this->get('doctrine')->getManager();
                    $ticket->setCreationDate(new \DateTime());
                    $ticket->setCreationUser($this->getUser()->getUsername());
                    $ticket->setStatus("Open");
                    $ticket->setTicketId($d);
                    $em->persist($ticket);
                    $em->flush();

                    $update_ticket = $em->getRepository('CRMSupportBundle:Ticket')->find($ticket->getId());
                    $update_ticket->setTicketId($d . $ticket->getId());
                    $em->persist($update_ticket);
                    $em->flush();

                    $this->getUserActivity("creation_user created new case: " . $update_ticket->getTicketId());

                    $this->get('session')->getFlashBag()->add('notice_success', 'success');
                    return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_overview'));

                    return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_view', array('id' => $ticket->getId())));
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('notice_error', 'error');
                    return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_overview'));
                }
            }
        }

        $tickets = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:Ticket', 'b')
                ->addOrderBy('b.creationDate', 'DESC')
                ->getQuery()
                ->getResult();

        return $this->render('CRMSupportBundle:Ticket:manage.html.twig', array(
                    'entity' => $ticket,
                    'tickets' => $tickets,
                    'categories' => $categories,
                    'form' => $form->createView()
        ));
    }

    public function createAction() {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();
        $categories = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:TicketCategory', 'b')
                ->getQuery()
                ->getResult();

        $datetime = new \DateTime("now");
        $d = $datetime->format('ymdh');

        $ticket = new Ticket();
        $form = $this->get('form.factory')->create(new TicketType(), $ticket);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $ticket->setCreationDate(new \DateTime());
                $ticket->setCreationUser($this->getUser()->getUsername());
                $ticket->setStatus("Open");
                $ticket->setTicketId($d);

                //set ticket category id
                // $ticket_cat = $em->getRepository('CRMSupportBundle:TicketCategory')->find('Billing Support');

                $em->persist($ticket);
                $em->flush();

                $update_ticket = $em->getRepository('CRMSupportBundle:Ticket')->find($ticket->getId());
                $update_ticket->setOrderReference($d . $ticket->getId());
                $em->persist($update_ticket);
                $em->flush();
//This page will redirect to View Profile.

                $this->getUserActivity("creation_user created new case: " . $update_ticket->getTicketId());
                return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_view', array('id' => $ticket->getId())));
            }
        }

        $tickets = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:Ticket', 'b')
                ->addOrderBy('b.creationDate', 'DESC')
                ->getQuery()
                ->getResult();

        $contacts = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMContactBundle:Contact', 'b')
                ->addOrderBy('b.firstname', 'ASC')
                ->where('b.creation_user = :id')
                ->setParameter('id', $this->getUser()->getUsername())
                ->getQuery()
                ->getResult();

        return $this->render('CRMSupportBundle:Ticket:create.html.twig', array(
                    'entity' => $ticket,
                    'tickets' => $tickets,
                    'contacts' => $contacts,
                    'categories' => $categories,
                    'form' => $form->createView()
        ));
    }

    public function closeAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('CRMSupportBundle:Ticket')->find($id);

        $status = "Close";
        $qb = $em->createQueryBuilder();
        $q = $qb->update('CRMSupportBundle:Ticket', 'u')
                ->set('u.status', '?1')
                ->where('u.id = ?2')
                ->setParameter(1, $status)
                ->setParameter(2, $id)
                ->getQuery();
        $p = $q->execute();

        $this->getUserActivity("creation_user closed case: " . $ticket->getTicketId());

        $this->get('session')->getFlashBag()->add('notice_close', 'close');
        return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_overview'));
    }

    public function reopenAction($id) {

        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('CRMSupportBundle:Ticket')->find($id);

        $status = "Re-Open";
        $qb = $em->createQueryBuilder();
        $q = $qb->update('CRMSupportBundle:Ticket', 'u')
                ->set('u.status', '?1')
                ->where('u.id = ?2')
                ->setParameter(1, $status)
                ->setParameter(2, $id)
                ->getQuery();
        $p = $q->execute();
        $this->getUserActivity("creation_user reopen case: " . $ticket->getTicketId());
        return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_overview'));
    }

    public function ticketAction($id) {
        $this->getTheme();
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('CRMSupportBundle:Ticket')->find($id);

        $categories = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:TicketCategory', 'b')
                ->getQuery()
                ->getResult();


        //Inserts data to Ticket Reply
        $ticket_reply = new TicketReply();
        $form = $this->get('form.factory')->create(new TicketReplyType(), $ticket_reply);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                try {
                    $em = $this->get('doctrine')->getManager();
                    $ticket_reply->setCreationDate(new \DateTime());
                    $ticket_reply->setTicketId($ticket->getId());
                    $ticket_reply->setCreationUser($this->getUser()->getUsername());
                    $em->persist($ticket_reply);
                    $em->flush();

                    $this->getUserActivity("creation_user created case: " . $ticket->getTicketId());
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('notice', 'error');
                }


//This page will redirect to View Profile.
                return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_view', array('id' => $ticket->getId())));
            }
        }

        $replies = $em->createQueryBuilder()
                ->select('b')
                ->from('CRMSupportBundle:TicketReply', 'b')
                ->addOrderBy('b.creationDate', 'DESC')
                ->where('b.ticket_id = :id')
                ->andWhere('b.ticket_reply_id IS NULL')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();

        $deleteForm = $this->createDeleteForm($id);
        return $this->render('CRMSupportBundle:Ticket:ticket.html.twig', array(
                    'categories' => $categories,
                    'ticket' => $ticket,
                    'replies' => $replies,
                    'form' => $form->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}/delete", name="CRMSupportBundle_ticket_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $delete_form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $delete_form->bind($request);

        if ($delete_form->isValid()) {
            try {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository('CRMSupportBundle:Ticket')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Ticket entity.');
                }

                $em->remove($entity);
                $em->flush();

                $this->getUserActivity("creation_user deleted case: " . $entity->getTicketId());

                $this->get('session')->getFlashBag()->add('success_delete', 'success deletion');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('notice_error', 'error');
            }
        }

        return $this->redirect($this->generateUrl('CRMSupportBundle_ticket_overview'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    public function getUserActivity($activiydesc) {
        $user_activity = new UserActivity();

        $form1 = $this->get('form.factory')->create(new UserActivityType(), $user_activity);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form1->bind($request);

            $user_activity->setActivityDesc($activiydesc);
            $user_activity->setDateAdded(new \DateTime());
            $user_activity->setModule('Case');
            $user_activity->setActivityUser($this->getUser()->getUsername());
            $em = $this->get('doctrine')->getManager();
            $em->persist($user_activity);
            $em->flush();
        }
    }

    public function getTheme() {
        $request = $this->getRequest();
        if ($request->query->get('skin')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('CRMUserBundle:User')->find($this->getUser()->getId());
            $user->setTheme($request->query->get('skin'));
            $em->flush();
        }
    }

}
