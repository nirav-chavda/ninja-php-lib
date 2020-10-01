<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    protected $commandName = 'make:model';
    protected $commandDescription = "To Create a Model";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Name of the model";

    protected $commandOptionName_Controller = "c"; # php command make:model Post --c
    protected $commandOptionDescription_Controller = 'If set, it will create a controller for the model';

    protected $commandOptionName_Table = "t"; # php command make:model Post --t
    protected $commandOptionDescription_Table = 'If set, it will create a table for the model';

    protected $commandOptionName_ControllerTable = "ct"; # php command make:model Post --ct
    protected $commandOptionDescription_ControllerTable = 'If set, it will create a controller and table for the model';

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
            ->addOption(
                $this->commandOptionName_Controller,
                null,
                InputOption::VALUE_NONE,
                $this->commandOptionDescription_Controller
             )
             ->addOption(
                $this->commandOptionName_Table,
                null,
                InputOption::VALUE_NONE,
                $this->commandOptionDescription_Table
             )
             ->addOption(
                $this->commandOptionName_ControllerTable,
                null,
                InputOption::VALUE_NONE,
                $this->commandOptionDescription_ControllerTable
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

        $filepath = $root . '\\app\\models\\';

        if(file_exists($filepath . $name . '.php')) {
            $output->writeln('Model is already exists');
        } else {

            try {

                include_once $root . '\\vendor\\nirav\\ninja-php\\consoleApp\\templates.php';

                $model_template = str_replace('Name',$name,$model_template);

                $filepath = $filepath . $name . '.php';

                file_put_contents($filepath, $model_template);

                $output->writeln('Model is created.');

                if ($input->getOption($this->commandOptionName_Controller)) {
                    $this->createController($input, $output, $name, $root, $controller_template);
                }
                else if($input->getOption($this->commandOptionName_ControllerTable)) {
                    $this->createController($input, $output, $name, $root, $controller_template);
                    $this->createTable($input, $output, $name, $root, $table_template);                    
                }
            }
            catch (\Exception $e) {
                $output->writeln($e);
            }
        }
    }

    protected function createController(InputInterface $input, OutputInterface $output,$name,$root, $controller_template) {
        
        if ( strpos($name,'Controller') === false ) {
            $name .= 'Controller';
        }

        $filepath = $root . '\\app\\controllers\\';

        $namespace = "App\\Controllers";

        if(file_exists($filepath . $name . '.php')) {
            $output->writeln('Controller is already exists');
        } else {

            try {

                $controller_template = str_replace('*ClassName*',$name,$controller_template);

                $controller_template = str_replace('*Namespace*',$namespace,$controller_template);

                $filepath = $filepath . $name . '.php';

                file_put_contents($filepath, $controller_template);

                $output->writeln('Controller is created.');
            }
            catch (\Exception $e) {
                $output->writeln($e);
            }
            
        }
    }

    protected function createTable(InputInterface $input, OutputInterface $output,$name,$root, $table_template) {
        
        $filepath = $root . '\\app\\database\\';

        if(file_exists($filepath . $name . '.php')) {
            $this->createTable($input, $output, $name, $root, $table_template);
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
