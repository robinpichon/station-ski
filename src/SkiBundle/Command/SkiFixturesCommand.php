<?php

namespace SkiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
        $output->writeln('Génération de <info>'.$input->getArgument('nbGenerate').'</info> entrées...');
        $comments = ['Super !', 'Très satisfait.', 'Nul.', 'Fuyez !', 'Bof bof'];

        //$em = $this->getDoctrine()->getManager();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        for($i = 0; $i <= $input->getArgument('nbGenerate'); $i++) {
            $review = new Review();
            $review->setStationId(rand(1, 10));
            $review->setUserId(rand(1, 10));
            $review->setNotation(rand(1, 5));
            $review->setComment($comments[rand(1, count($comments))-1]);
            $em->persist($review); // Mettre dans une file d'attente
        }

        $em->flush(); // Inserer les objets en file d'attente dans la base de données
        $output->writeln('<info>Génération OK.</info>');
    }

}
