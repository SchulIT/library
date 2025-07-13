<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

#[AsCommand('app:setup')]
class SetupCommand {

    public function __construct(private readonly Connection $dbalConnection,
                                private readonly PdoSessionHandler $pdoSessionHandler) {}

    public function __invoke(OutputInterface $output, SymfonyStyle $io): int {
        $this->setupSessions($io);

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function setupSessions(SymfonyStyle $io): void {
        $io->section('Sessions');
        $io->comment('Erstelle sessions-Tabelle');

        $sql = "SHOW TABLES LIKE 'sessions';";
        $row = $this->dbalConnection->executeQuery($sql);

        if($row->fetchAssociative() === false) {
            $io->write('Tabelle existiert nicht, lege an...');
            $this->pdoSessionHandler->createTable();
            $io->success('Tabelle angelegt');
        } else {
            $io->success('Tabelle existiert bereits');
        }
    }
}