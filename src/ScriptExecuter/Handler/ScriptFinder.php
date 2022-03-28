<?php
namespace ScriptExecuter\Handler;

use Symfony\Component\Filesystem\Filesystem;
use ScriptExecuter\Commands\ScriptExecuter;
use Symfony\Component\Finder\Finder;

/**
 * Main class of the script finder tool
 * @author Matheus Marqui <matheus.701@live.com>
 */
Class ScriptFinder{
    protected const REGISTERED_SCRIPTS_FILENAME = 'scripts.json';
    protected const SCRIPTS_FOLDER = 'Scripts';
    public $scriptsRegistered;
    protected $scriptCode;
    protected $scriptDir;
    protected $fs;

    public function __construct()
    {
        $this->scriptsRegistered = ScriptExecuter::WORK_DIR . self::REGISTERED_SCRIPTS_FILENAME;
        $this->scriptDir = ScriptExecuter::WORK_DIR . '/' . self::SCRIPTS_FOLDER;
        $this->fs = new Filesystem();
    }

    /**
     * Returns all scripts registered in json file.
     * @return array
     **/
    public function getRegisteredScripts(){
        if($link = $this->fs->readlink($this->scriptsRegistered, true)){
            return json_decode(file_get_contents($link), true);
        }
    }

    /**
     * Returns false if the script class doesn't exists or true if exists.
     * @param string $className script code to be executed
     * @return boolean
     **/
    public function isClassCreated($className){
        $finder = Finder::create()->files()->name($className . '.php')->in(
            $this->scriptDir
        );
        return $finder->hasResults() ? true : false;
    }

    /**
     * Returns false if the script isn't registered and class name if it is.
     * @param string $scriptCode script code to be executed
     * @return string|boolean 
     **/
    protected function isRegistered(string $scriptCode){
        if(array_key_exists($scriptCode, $registerdScripts = $this->getRegisteredScripts())){
            return $registerdScripts[$scriptCode];
        }else return false;
    }

    /**
     * Return instance of Script if found or false if not
     * @param string $scriptCode script code to be executed
     * @return object|false 
     **/
    public function find($scriptCode, $io){
        if($className = $this->isRegistered($scriptCode)){
            if($this->isClassCreated($className)){
                $instanceOfScript = '\\ScriptExecuter\Scripts\\' . $className . '\\' . $className;
                return new $instanceOfScript($io);
            }else return false;
        }else return false;
    }
}
