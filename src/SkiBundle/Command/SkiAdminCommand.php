<?php

namespace SkiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SkiBundle\Entity\User;

class SkiAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ski:admin')
            ->setDescription('Create admin user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Generating admin account... ');

        $passwordEncoder = $this->getContainer()->get('security.password_encoder');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Generate admin
        $email = 'admin_'.uniqid().'@admin.fr';
        $password = md5(uniqid());

        $user = new User();
        $user->setFirstname('Admin')
            ->setLastname('ADMIN')
            ->setEmail($email)
            ->setPassword($passwordEncoder->encodePassword($user, $password))
            ->setAvatar('default.png')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $em->persist($user);
        $em->flush();
        $output->writeln('<info>OK</info>');

        $output->writeln('=============================================================');
        $output->writeln('Administrator account credentials');
        $output->writeln('Email: <info>'.$email.'</info>');
        $output->writeln('Password: <info>'.$password.'</info>');
        $output->writeln('=============================================================');
    }

}
