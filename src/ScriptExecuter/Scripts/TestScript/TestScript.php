<?php
namespace ScriptExecuter\Scripts\TestScript;

Class TestScript{
    protected $io;

    public function __construct($io)
    {
        $this->io = $io;
    }

    public function execute(){
        $this->io->text("Hello World!");
    }
}
?>