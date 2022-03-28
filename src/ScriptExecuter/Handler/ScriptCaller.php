<?php
namespace ScriptExecuter\Handler;

use Symfony\Component\Console\Style\SymfonyStyle;
use ScriptExecuter\Handler\ScriptFinder;

/**
 * Main class of the script caller tool
 * @author Matheus Marqui <matheus.701@live.com>
 */
Class ScriptCaller{
    protected $scriptCode;
    protected $io;

    public function __construct(
        string $scriptCode,
        SymfonyStyle $io
    ){
        $this->scriptCode = $scriptCode;
        $this->io = $io;
    }

    public function call() : void{
        $finder = new ScriptFinder();
        try{
            $instanceScript = $finder->find($this->scriptCode, $this->io);
            if($instanceScript){
                $this->io->success("✔ Class " . get_class($instanceScript) . " successfully launched. 😀");
                $instanceScript->execute();
            }else{
                $this->io->warning("Unfortunately, the script class could not be found.");
            }
        }catch(\Exception $e){
            $this->io->error($e);
        }
    }
    
}
?>