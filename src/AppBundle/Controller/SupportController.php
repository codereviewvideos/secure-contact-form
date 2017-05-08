<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Entity\SupportRequest;
use AppBundle\Form\Type\SupportRequestFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class SupportController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(UserInterface $member)
    {
        /**
         * @var $member Member
         */
        $supportRequest = (new SupportRequest())
            ->setEmail($member->getEmail())
            ->setMember($member)
        ;

        $form = $this->createForm(SupportRequestFormType::class, $supportRequest, [
            'action' => $this->generateUrl('handle_form_submission'),
        ]);

        return $this->render('support/index.html.twig', [
            'our_form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/form-submission", name="handle_form_submission")
     * @Method("POST")
     */
    public function handleFormSubmissionAction(Request $request)
    {
        $form = $this->createForm(SupportRequestFormType::class);

        $form->handleRequest($request);

        if ( ! $form->isSubmitted() || ! $form->isValid()) {

            return $this->redirectToRoute('homepage');
        }

        /**
         * @var $supportRequest SupportRequest
         */
        $supportRequest = $form->getData();

        dump($supportRequest);

        $message = \Swift_Message::newInstance()
            ->setSubject('Support Form Submission')
            ->setFrom($supportRequest->getEmail())
            ->setTo('cexzaukk@sharklasers.com')
            ->setBody(
                $supportRequest->getMessage(),
                'text/plain'
            )
        ;

        $this->get('mailer')->send($message);


        $em = $this->getDoctrine()->getManager();
        $em->persist($supportRequest);
        $em->flush();


        $this->addFlash('success', 'Your message was sent!');

        return $this->redirectToRoute('homepage');
    }
}
