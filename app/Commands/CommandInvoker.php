<?php

namespace App\Commands;

class CommandInvoker
{
    private $command;

    public function setCommand(Command $command)
    {
        $this->command = $command;
        return $this;
    }

    public function executeCommand()
    {
        if ($this->command) {
            return $this->command->execute();
        }

        throw new \Exception("No command has been set");
    }
}