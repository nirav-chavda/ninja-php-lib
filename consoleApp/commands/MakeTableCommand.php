<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeTableCommand extends Command
{
    protected $commandName = 'make:table';
    protected $commandDescription = "To Make Table Structures";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Name of the table";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::REQUIRED,
                $this->commandArgumentDescription
            )
        ;
    }

    protected function configureManager()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::REQUIRED,
                $this->commandArgumentDescription
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);

        $name = explode(' ',$name);

        foreach ($name as $key => $value) {
            $name[$key] = ucwords($value);
        }

        $name = implode(' ',$name);

        $name = str_replace(' ', '', $name);

        $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

        $filepath = $root . '\\app\\database\\';

        if(file_exists($filepath . $name . '.php')) {
            $output->writeln('Table is already exists');
        } else {

            try {

                include_once $root . '\\vendor\nirav\ninja-php\\consoleApp\\templates.php';

                $filepath = $filepath . $name . '.php';

                file_put_contents($filepath, $table_template);

                $output->writeln('Table is created.');
            }
            catch (\Exception $e) {
                $output->writeln($e);
            }
        }   
    }
}
