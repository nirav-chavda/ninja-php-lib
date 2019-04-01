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

    protected $commandOptionName = "c"; // php command make:model Post --c
    protected $commandOptionDescription = 'If set, it will create a controller for the model';  

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
                $this->commandOptionName,
                null,
                InputOption::VALUE_NONE,
                $this->commandOptionDescription
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
            $output->writeln('Model is already existed');
        } else {

            try {

                include_once $root . '\\vendor\\nirav\\ninja-php\\consoleApp\\templates.php';

                $model_template = str_replace('Name',$name,$model_template);

                $filepath = $filepath . $name . '.php';

                file_put_contents($filepath, $model_template);

                $output->writeln('Model is created.');

                if ($input->getOption($this->commandOptionName)) {
    
                    if ( strpos($name,'Controller') === false ) {
                        $name .= 'Controller';
                    }
    
                    $filepath = $root . '\\app\\controllers\\';

                    if(file_exists($filepath . $name . '.php')) {
                        $output->writeln('Controller is already existed');
                    } else {

                        try {

                            $controller_template = str_replace('Name',$name,$controller_template);

                            $filepath = $filepath . $name . '.php';

                            file_put_contents($filepath, $controller_template);

                            $output->writeln('Controller is created.');
                        }
                        catch (\Exception $e) {
                            $output->writeln($e);
                        }
                    }
                }
            }
            catch (\Exception $e) {
                $output->writeln($e);
            }
        }
    }
}