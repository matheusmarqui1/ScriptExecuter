<?php

namespace ScriptExecuter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use ScriptExecuter\Handler\ScriptCaller;
use ScriptExecuter\Handler\ScriptFinder;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;


/**
 * Main class of the script executer tool
 * @author Matheus Marqui <matheus.701@live.com>
 */

class ScriptExecuter extends Command
{
    public const WORK_DIR = __DIR__ . '/../';
    protected $io;
    protected $scriptFinder;

    public function __construct()
    {
        $this->scriptFinder = new ScriptFinder();
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('execute')
            ->setDescription('Executa um script específico.')
            ->addOption(
                'script',
                's',
                InputOption::VALUE_OPTIONAL,
                'Script a ser executado.',
                false
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new OutputFormatterStyle('#4ef557', '', ['bold']);
        $output->getFormatter()->setStyle('scriptexecuter', $outputStyle);
        $this->io = new SymfonyStyle($input, $output);
        $this->io->text([
            "
            <scriptexecuter>

                            ███████╗ ██████╗██████╗ ██╗██████╗ ████████╗                      
                            ██╔════╝██╔════╝██╔══██╗██║██╔══██╗╚══██╔══╝                      
                            ███████╗██║     ██████╔╝██║██████╔╝   ██║                         
                            ╚════██║██║     ██╔══██╗██║██╔═══╝    ██║                         
                            ███████║╚██████╗██║  ██║██║██║        ██║                         
                            ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝╚═╝        ╚═╝                                                                     
                ███████╗██╗  ██╗███████╗ ██████╗██╗   ██╗████████╗███████╗██████╗ 
                ██╔════╝╚██╗██╔╝██╔════╝██╔════╝██║   ██║╚══██╔══╝██╔════╝██╔══██╗
                █████╗   ╚███╔╝ █████╗  ██║     ██║   ██║   ██║   █████╗  ██████╔╝
                ██╔══╝   ██╔██╗ ██╔══╝  ██║     ██║   ██║   ██║   ██╔══╝  ██╔══██╗
                ███████╗██╔╝ ██╗███████╗╚██████╗╚██████╔╝   ██║   ███████╗██║  ██║
                ╚══════╝╚═╝  ╚═╝╚══════╝ ╚═════╝ ╚═════╝    ╚═╝   ╚══════╝╚═╝  ╚═╝
                                    <fg=yellow;options=bold,blink>Created by Matheus Marqui</>                              

            </>                                                                
            "
        ]);
        if (!$input->getOption('script') || $input->getOption('script') === null) {
            $this->io->text("<options=bold>Hey there, you need to choose a script to run:</>");
            $choice = explode(' ⟶ ', $this->io->choice('script_code ⟶ script_class', array_map(function ($class, $key) {
                return "$key ⟶ $class";
            }, $scripts = $this->scriptFinder->getRegisteredScripts(), array_keys($scripts))))[0];
            $input->setOption('script', $choice);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($scriptCode = $input->getOption('script')) {
            $script = new ScriptCaller(
                $scriptCode,
                $this->io
            );
            $script->call();
        }
        return Command::SUCCESS;
    }
}
