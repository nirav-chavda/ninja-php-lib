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

        $name = str_split($args);

        if( in_array('\\',$name) ) {
        
            $args = str_replace('\\','/',$args);
            //$output->writeln('Controller Name Should Not Have Any Special Character !');
        
        } 

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

        $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));            

        $filepath = $root . '\\app\\controllers\\';
        $namespace = "App\\Controllers\\";

        if($size!=1) {
            for($i=0;$i<$size-1;$i++) {
                if (!file_exists($filepath . $args[$i])) {
                    mkdir($filepath . $args[$i], 0777, true);
                }
                $filepath .= $args[$i] . '\\';
                $namespace .= $args[$i] . '\\';
            }
        }

        $namespace = rtrim($namespace,'\\');

        if(file_exists($filepath . $name . '.php')) {
            $output->writeln('Controller is already existed');
        } else {

            try {

                include_once $root . '\\vendor\nirav\ninja-php\\consoleApp\\templates.php';

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
}