<?php
namespace ScriptExecuter\Scripts\TestScript;
use Symfony\Component\Console\Style\SymfonyStyle;
Class TestScript{
    protected $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function execute(){
        $this->io->text("Hello World!");
    }
}
?>