<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\UserRepository;
use PhpParser\Node\Stmt\If_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    #[Route('/', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        //vérification si l'utilisateur est déjà connecté via remember me
        //si oui direction vers la page d'accueil
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trip_home');
        }

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
    public function editProfile(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        //récupération de l'utilisateur par
        $user= $userRepository->find($this->getUser());
        $profileForm = $this->createForm(EditProfileType::class, $user);

        $profileForm->handleRequest($request);


        //Clic sur le bouton enregistrer mise à jour du profil avec message
        if ($profileForm->get('Enregistrer') && $profileForm->isSubmitted() && $profileForm->isValid()) {

            //vérification mot de passe
            if ($profileForm['password']->getData()) {

                    //récupération du nouveau mot de passe saisi
                    $newPassword = $profileForm['password']->getData();

                    //effectuer le hachage du mot de passe
                    $hashed = $hasher->hashPassword($user, $newPassword);

                    //setter le nouveau mot de passe à l'utilisateur et envoyer en bdd
                    $user->setPassword($hashed);
                    $userRepository->add($user, true);

                    //retourner à la page home et afficher un message
                    $this->addFlash('message', 'votre mot de passe a bien été modifié');
                    return $this-> redirectToRoute('trip_home');
            }

            if (!$profileForm['password']->getData()) {

                //mise à jour du profil et envoi à la bdd
                $userRepository->add($user, true);

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('trip_home');
            }

        }

        //Clic sur annuler : retour à la page d'accueil avec message
//        if ($profileForm->get('Annuler') && $profileForm->get('Annuler')->isSubmitted()) {
//
//            $this->addFlash('message', 'Annulation');
//            return $this->redirectToRoute('trip_home');
//        }

        return $this->render('user/edit.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
      }

    #[Route(path: '/profile/{id}', name: 'otherProfile')]
    public function showOtherProfile($id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);

        return $this->render('user/other-profile.html.twig', [
            'id' => $id,
            'user' => $user,
        ]);
    }

}