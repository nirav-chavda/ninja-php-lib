<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DBMoveCommand extends Command {

    protected $commandName = 'db:move';
    protected $commandDescription = "It will create tables from the database folder into database";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::REQUIRED
            )
        ;
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

        # Load All Files from the directory

        $log_directory = $root . "\\app\\database";

        $results_array = array();

        if (is_dir($log_directory))
        {
            if ($handle = opendir($log_directory))
            {
                while(($file = readdir($handle)) !== FALSE)
                {
                    $results_array[] = $file;
                }
                closedir($handle);
            }
        }

        # Output findings
        foreach($results_array as $value)
        {
            if($value != '.' && $value != '..') {
                shell_exec('php ' . $log_directory . '\\' . $value);
                $output->writeln($value . " moved" . "\n");
            }
        }        
    }
}
