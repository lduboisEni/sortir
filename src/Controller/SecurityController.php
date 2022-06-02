<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Faker\Generator;
use PhpParser\Node\Stmt\If_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;


    #[Route('/', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the security error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

    }

    #[Route(path: '/edit', name: 'edit')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $profileForm = $this->createForm(EditProfileType::class, $user);

        $profileForm->handleRequest($request);

        //Clic sur le bouton enregistrer mise à jour du profil avec message
        if ($profileForm->get('Enregistrer') && $profileForm->isSubmitted() && $profileForm->isValid()) {

            //vérification mot de passe
            if (!$profileForm['password']->getData() && !$profileForm['confirmPassword']->getData()) {
                if ($profileForm['password']->getData() === $profileForm['confirmPassword']->getData()) {

                    //effectuer le hachage du mot de passe
                    $plainPassword = $this->faker->password;
                    $hashed = $this->hasher->hashPassword($user, $plainPassword);

                    //setter le nouveau mot de passe à l'utilisateur et envoyer en bdd
                    $user->setPassword($hashed);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    //retourner à la page home et afficher un message
                    $this->addFlash('message', 'votre mot de passe a bien été modifié');
                    return $this-> redirectToRoute('trip_home');
                }
                else {
                    //afficher un message au cas où les 2 mots de passe ne sont pas identiques
                    $this->addFlash('message', 'mot non identique');
                }
            }
            else {
                //afficher un message au cas où les 2 mots de passe ne sont pas identiques
                $this->addFlash('message', 'renseigner les deux mots de passe');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('trip_home');
        }

        //Clic sur annuler : retour à la page d'accueil avec message
        if ($profileForm->get('Annuler') && $profileForm->isSubmitted()) {

            $this->addFlash('message', 'Annulation');
            return $this->redirectToRoute('trip_home');
        }

        return $this->render('user/edit.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }


}