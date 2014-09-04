<?php
namespace Acme\TestTaskBundle\Security\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider as BaseClass;
/**
 * Description of EntityUserProvider
 *
 * @author bilal
 */
class EntityUserProvider extends BaseClass {


    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        $username = $response->getUsername();
        $strka = $this->properties[$resourceOwnerName];
        $user = $this->repository->findOneBy(array($this->properties[$resourceOwnerName] => $username));

        if (null === $user) {
            $user = new $this->class($username);
            //$username, $password, $salt, array $roles
            $data = $response->getResponse();
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setFacebookId($data['id']);
            //$token1 = $response->getAccessToken();
            //$token2 = $response->getOAuthToken()->getRawToken();
            $user->setFacebookAccessToken($response->getAccessToken());
            $user->setPassword(strval(rand(10000, 99999)));
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

//    public function refreshUser(UserInterface $user)
//    {
//        if (!$this->supportsClass(get_class($user))) {
//            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
//        }
//
//        $user = $this->repository->findOneBy(array('id' => $user->getId()));
//        if (!$user) {
//            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
//        }
//
//        return $user;
//    }    
}
