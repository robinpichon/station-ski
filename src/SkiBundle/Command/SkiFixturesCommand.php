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
            ->setDescription('Load fixtures.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stations = ['Aussois', 'Avoriaz', 'Les 2 alpes'];
        $station_desc = [
          'Située dans le département de Savoie, en Haute Maurienne Vanoise, sur un plateau orienté plein sud à 1500 m d’altitude, la station de ski d’Aussois séduit les familles en vacances à la neige. Son cadre reposant et traditionnel ainsi que les infrastructures et services réservés aux familles (station labélisée Famille Plus depuis 2006) sont ses véritables atouts.',
          'La station de ski d\'Avoriaz est située au cœur du domaine des Portes du Soleil, sur un plateau exposé plein sud. Née d\'un défi écologique avant l\'âge, Avoriaz est une station entièrement piétonne, interdite aux voitures, où tous les hébergements sont accessibles à ski et où les rues sont des pistes de ski.',
          'Station de ski phare du département de l\'Isère (avec sa consœur de l\'Alpe d\'Huez), les 2 Alpes jouie d\'une réputation internationale grâce notamment à son domaine d\'altitude (le plus grand domaine de ski sur glacier d\'Europe) permettent de pratiquer le ski jusqu’à 3600 mètres d’altitude. Le glacier est en effet un atout sérieux, l\'assurance de skier sur une neige naturelle chaque année, hiver comme été...'
        ];
        $locations = ['Savoie (73)', 'Haute Savoie (74)', 'Isère (38)'];
        $places_id = ['ChIJu26ygcESikcRCXyyB2KEqTM', 'ChIJeaU1B1KmjkcRALIogy2rCAo', 'ChIJu26ygcESikcRCXyyB2KEqTM'];
        $comments = ['Super !', 'Très satisfait.', 'Nul.', 'Fuyez !', 'Bof bof'];

        $passwordEncoder = $this->getContainer()->get('security.password_encoder');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Generate stations
        $output->write('Generating 3 stations... ');
        foreach($stations as $key => $value) {
            $station = new Station();
            $station->setName($value)
                    ->setLocation($locations[$key])
                    ->setPlaceId($places_id[$key])
                    ->setDescription($station_desc[$key])
                    ->setImage(str_replace(' ', '', strtolower($value).'.jpg'));

            $em->persist($station);
        }

        $em->flush();
        $output->writeln('<info>OK</info>');

        // Generate users
        $users = [];
        $output->write('Generating 5 users... ');
        for($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstname('Test')
                ->setLastname('Test')
                ->setEmail('test'.$i.'@test.fr')
                ->setPassword($passwordEncoder->encodePassword($user, 'test123'))
                ->setAvatar('default.png')
                ->setRoles(['ROLE_USER']);

            array_push($users, $user);
            $em->persist($user);
        }
        $output->writeln('<info>OK</info>');

        // Generate reviews
        $output->write('Generating 10 reviews... ');
        for($i = 0; $i <= 10; $i++) {
            $review = new Review();
            $review->setStation($em->getRepository(Station::class)->findOneById(rand(1, count($stations))))
                    ->setUser($users[rand(1, count($users))-1])
                    ->setStatus(true)
                    ->setNotation(rand(1, 5))
                    ->setComment($comments[rand(1, count($comments))-1]);

            $em->persist($review);
        }

        $em->flush();
        $output->writeln('<info>OK</info>');

        // Clear avatars directory
        $output->write('Clearing avatars directory... ');
        $avatars_dir = $this->getContainer()->getParameter('avatars_directory');
        $c_del = 0;
        foreach(scandir($avatars_dir) as $key => $file) {
            if($file !== 'default.png' && $key > 1) {
                unlink($avatars_dir.'/'.$file);
                $c_del++;
            }
        }

        $output->writeln('<info>OK ('.$c_del.' file(s) deleted)</info>');
    }

}
