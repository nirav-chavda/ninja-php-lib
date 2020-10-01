<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{

    protected $commandName = 'serve';
    protected $commandDescription = "It will start a local development server";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('Magic Happens At - 127.0.0.1:8000');

        shell_exec('php -S 127.0.0.1:8000 -t public/');
    }
    protected function executeCommand(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('Magic Happens At - 127.0.0.1:8000');

        shell_exec('php -S 127.0.0.1:8000 -t public/');
    }
}
