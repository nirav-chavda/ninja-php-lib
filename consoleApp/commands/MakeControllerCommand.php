<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected $commandName = 'make:controller';
    protected $commandDescription = "To Make a Controller";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Name of the controller";

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = $input->getArgument($this->commandArgumentName);

        $args = explode('/',$args);

        $size = count($args);

        $name = $args[$size-1];

        $name = explode(' ',$name);

        foreach ($name as $key => $value) {
            $name[$key] = ucwords($value);
        }

        $name = implode(' ',$name);

        $name = str_replace(' ', '', $name);
        if ( strpos($name,'Controller') === false ) {
           $name .= 'Controller';
        }

        $root = substr(__DIR__,0,strpos(__DIR__,'\src'));

        $filepath = $root . '\\app\\controllers\\';

        if($size!=1) {
            for($i=0;$i<$size-1;$i++) {
                if (!file_exists($filepath . $args[$i])) {
                    mkdir($filepath . $args[$i], 0777, true);
                }
                $filepath .= $args[$i] . '\\';
            }
        }

        if(file_exists($filepath . $name . '.php')) {
            $output->writeln('Controller is already created');
        } else {

            try {

                include_once $root . '\\src\\consoleApp\\templates.php';

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