<?php

namespace SkiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SkiBundle\Entity\Station;
use SkiBundle\Entity\Review;

class SkiFixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ski:fixtures')
            ->setDescription('Chargement du jeu d\'essai.')
            ->addArgument('nbGenerate', InputArgument::REQUIRED, 'Nombre d\'entrées à générer.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Génération de 3 stations et '.$input->getArgument('nbGenerate').' reviews...');
        $stations = ['Aussois', 'Avoriaz', 'Les 2 alpes'];
        $comments = ['Super !', 'Très satisfait.', 'Nul.', 'Fuyez !', 'Bof bof'];

        //$em = $this->getDoctrine()->getManager();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Génération des stations
        foreach($stations as $key => $value) {
            $station = new Station();
            $station->setName($value);
            $station->setDescription('Description de la station');
            $station->setImage(strtolower($value).'.jpg');
            $em->persist($station); // Mettre dans la file d'attente
        }

        // Génération des reviews
        for($i = 0; $i <= $input->getArgument('nbGenerate'); $i++) {
            $review = new Review();
            $review->setStationId(rand(1, 3));
            $review->setUserId(rand(1, 10));
            $review->setNotation(rand(1, 5));
            $review->setComment($comments[rand(1, count($comments))-1]);
            $em->persist($review); // Mettre dans la file d'attente
        }

        $em->flush(); // Inserer les objets en file d'attente dans la base de données
        $output->writeln('<info>Génération OK.</info>');
    }

}
