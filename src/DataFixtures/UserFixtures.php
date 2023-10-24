<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserJobRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    private $profileRepository;
    private $userJobRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, ProfileRepository $profileRepository, UserJobRepository $jobRepository)
    {
        $this->encoder           = $encoder;
        $this->profileRepository = $profileRepository;
        $this->userJobRepository = $jobRepository;
    }

    public function load(ObjectManager $manager): void
    {
        //ajouter un nouveau utilisateur  dont le profil est un Direction générale

        $profile = $this->profileRepository->findOneBy(['acronyme' => 'DG']);
        $userJob = $this->userJobRepository->findOneBy(['label' => 'Directeur']);

        $user = new User();
        $encodedPassword = $this->encoder->encodePassword($user, 'Test2000!');
        $user->setEmail('Dg@clinfile.com');
        $user->setFirstName('DG');
        $user->setLastName('Altra');
        $user->setPassword($encodedPassword);
        $user->setIsSuperadmin(true);
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $user->setPhone('0102030405');
        $user->setProfile($profile);
        $user->setJob($userJob);
        $manager->persist($user);

        // ajouter un nouveau utilisateur  dont le profil est un salarié

        $profile1 = $this->profileRepository->findOneBy(['acronyme' => 'SA']);
        $userJob1 = $this->userJobRepository->findOneBy(['label' => 'Salarié']);
        $user1 = new User();
        $encodedPassword = $this->encoder->encodePassword($user1, 'Test2000!');
        $user1->setEmail('Sa@clinfile.com');
        $user1->setFirstName('SA');
        $user1->setLastName('Altra');
        $user1->setPassword($encodedPassword);
        $user1->setIsSuperadmin(false);
        $user1->setPhone('0102030407');
        $user1->setRoles(["ROLE_USER"]);
        $user1->setProfile($profile1);
        $user1->setJob($userJob1);
        $manager->persist($user1);

        // ajouter un nouveau utilisateur  dont le profil est un support

        $profile2 = $this->profileRepository->findOneBy(['acronyme' => 'FS']);
        $userJob2 = $this->userJobRepository->findOneBy(['label' => 'Supports']);
        $user2 = new User();
        $encodedPassword = $this->encoder->encodePassword($user2, 'Test2000!');
        $user2->setEmail('Fs@clinfile.com');
        $user2->setFirstName('FS');
        $user2->setLastName('Clinfie');
        $user2->setPassword($encodedPassword);
        $user2->setIsSuperadmin(false);
        $user2->setPhone('0102030407');
        $user2->setRoles(["ROLE_USER"]);
        $user2->setProfile($profile2);
        $user2->setJob($userJob2);
        $manager->persist($user2);

        $manager->flush();


    }

    public static function getGroups(): array
    {
        return ['userCreate'];
    }
}
