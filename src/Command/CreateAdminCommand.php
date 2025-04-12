<?php

namespace App\Command;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:admin',
    description: 'Créer un compte administrateur manuellement.',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new User();
        $admin->setEmail('admin@locadrive.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $admin->setFirstName('Admin');
        $admin->setLastName('Locadrive');
        $admin->setDriverLicenseDate(new \DateTime('2000-01-01')); // date fictive
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'Admin1234') // à changer avant prod ;)
        );

        $this->em->persist($admin);
        $this->em->flush();

        $output->writeln('<info>✅ Admin créé avec succès !</info>');

        return Command::SUCCESS;
    }
}