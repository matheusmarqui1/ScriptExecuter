<?php

namespace ScriptExecuter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use ScriptExecuter\Handler\ScriptFinder;


/**
 * Class of the script creation tool
 * @author Matheus Marqui <matheus.701@live.com>
 * @todo implement the interact method to check if there's an actual script code and class name in the args
 */

class ScriptCreator extends Command
{
    public const WORK_DIR = __DIR__ . '/../';
    public const PATH_TO_MODEL = __DIR__ . '/../Model/';
    public const PATH_TO_SCRIPTS = __DIR__ . '/../Scripts/';
    public const MODEL_FILENAME = 'class_model.model';
    protected $scriptFinder;
    protected $fs;
    protected $io;

    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->scriptFinder = new ScriptFinder();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('create')
            ->setDescription('Create a new Script.')
            ->addOption(
                'script',
                's',
                InputOption::VALUE_OPTIONAL,
                'Script to be created.',
                false
            )
            ->addOption(
                'class-name',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Script class name.',
                false
            );
    }

    protected function saveClassFile(string $fileName, string $modelContent)
    {
        if (file_put_contents($fileName, $modelContent))
            return true;
        else return false;
    }

    /**
     * Gets the snippet of the class to be created (class_model.model)
     * 
     * @param string $modelFilename snippet filename (it can be found in Model folder)
     * @return string|false snippet on success or false on error
     **/
    protected function getModel(string $modelFilename)
    {
        if ($link = $this->fs->readlink(self::PATH_TO_MODEL . $modelFilename, true))
            return file_get_contents($link);
        else return false;
    }

    /**
     * Create the script folder and class file.
     * 
     * @param string $className script's class name (the folder of the script will have the same name)
     * @return boolean true on success or false on error
     **/
    protected function createClassFile(string $className)
    {
        try {
            if(!is_dir(self::PATH_TO_SCRIPTS)) mkdir(self::PATH_TO_SCRIPTS);
            $this->fs->mkdir($pathToClass = self::PATH_TO_SCRIPTS . $className . '/');
            if ($this->saveClassFile(
                $pathToClass . $className . '.php',
                str_replace('{{CLASS_NAME}}', $className, $this->getModel(self::MODEL_FILENAME))
            )) return true;
            else return false;
        } catch (IOExceptionInterface $exception) {
            $this->io->error("An error occurred while creating your directory at " . $exception->getPath());
        }
    }
    
    /**
     * Register a script.
     *
     * This function is responsible for creating a new script and register it. 
     * @param string $scriptCode script's code
     * @param string $className script's class name (the folder of the script will have the same name)
     * @return boolean true on success or false on error
     **/
    protected function register(string $scriptCode, string $className)
    {
        if (!$this->createClassFile($className)) return false;

        $registeredScripts = $this->scriptFinder->getRegisteredScripts();

        if (!array_key_exists($scriptCode, $registeredScripts)) {

            $registeredScripts[$scriptCode] = $className;

            if ($link = $this->fs->readlink($this->scriptFinder->scriptsRegistered, true)) {
                $file = fopen($link, "w");
                if(fwrite($file, json_encode($registeredScripts, JSON_PRETTY_PRINT))){
                    fclose($file);
                    return true;
                }
            } else return false;

        } else return false;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        if ($input->getOption('script') && $input->getOption('class-name')) {
            $this->register($input->getOption('script'), $input->getOption('class-name'));
        }
        return Command::SUCCESS;
    }
}
