<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:add-client')]
class AddClientCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);
        $firstname = $io->ask('Entrez le prénom du client');
        $lastname = $io->ask('Entrez le nom du client');
        $email = $io->ask('Entrez l’email du client');
        $phone = $io->ask('Entrez le numéro de téléphone du client');
        $address = $io->ask('Entrez adresse du client');

        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phone);
        $client->setAddress($address);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('Client ajouté avec succès !');

        return Command::SUCCESS;
    }
}
