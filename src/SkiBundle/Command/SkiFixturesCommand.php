<?php

namespace SkiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SkiBundle\Entity\Station;
use SkiBundle\Entity\Review;
use SkiBundle\Entity\User;

class SkiFixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ski:fixtures')
            ->setDescription('Chargement du jeu d\'essai.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Génération de 3 stations, 5 users et 10 reviews...');
        $stations = ['Aussois', 'Avoriaz', 'Les 2 alpes'];
        $locations = ['Savoie (73)', 'Haute Savoie (74)', 'Isère (38)'];
        $places_id = ['ChIJu26ygcESikcRCXyyB2KEqTM', 'ChIJeaU1B1KmjkcRALIogy2rCAo', 'ChIJu26ygcESikcRCXyyB2KEqTM'];
        $comments = ['Super !', 'Très satisfait.', 'Nul.', 'Fuyez !', 'Bof bof'];

        $passwordEncoder = $this->getContainer()->get('security.password_encoder');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Génération des stations
        foreach($stations as $key => $value) {
            $station = new Station();
            $station->setName($value)
                    ->setLocation($locations[$key])
                    ->setPlaceId($places_id[$key])
                    ->setDescription('Description de la station')
                    ->setImage(str_replace(' ', '', strtolower($value).'.jpg'));

            $em->persist($station);
        }

        $em->flush();

        // Génération des users
        for($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstname('Test')
                ->setLastname('Test')
                ->setEmail('test'.$i.'@test.fr')
                ->setPassword($passwordEncoder->encodePassword($user, 'test123'))
                ->setAvatar('default.png')
                ->setRoles(['ROLE_USER']);

            $em->persist($user);
        }

        // Génération des reviews
        for($i = 0; $i <= 10; $i++) {
            $review = new Review();
            $review->setStation($em->getRepository(Station::class)->findOneById(rand(1, count($stations))))
                    ->setUser($user)
                    ->setNotation(rand(1, 5))
                    ->setComment($comments[rand(1, count($comments))-1]);

            $em->persist($review);
        }

        $em->flush();
        $output->writeln('<info>Génération OK.</info>');
    }

}
