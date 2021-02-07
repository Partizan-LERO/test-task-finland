<?php


namespace App\Console;


use Framework\Console\ConsoleOutput;
use Framework\Console\ICommand;
use Framework\Exceptions\ClassDoesNotImplementCommandInterfaceException;

/**
 * Class Handler
 * @package App\Console
 */
class Handler
{
    /**
     * Register new commands here
     * @return array
     */
    private function commands(): array
    {
        return [
            'parse_posts_from_sm_api' => ParsePostsFromApiCommand::class,
        ];
    }

    /**
     * @param string $commandName
     * @throws ClassDoesNotImplementCommandInterfaceException
     */
    public function run(string $commandName): void
    {
        $commandsList = $this->commands();

        foreach ($commandsList as $name => $command) {
            if ($commandName === $name) {
                $c = new $command();
                if ($c instanceof ICommand) {
                    $c->execute();
                } else {
                    throw new ClassDoesNotImplementCommandInterfaceException();
                }
                exit(0);
            }
        }


        ConsoleOutput::error('Command not found');
    }

    public function list(): void
    {
        $commandList = $this->commands();

        ConsoleOutput::info('The list of available commands are: ');

        ConsoleOutput::info('-----------------------------------');

        foreach ($commandList as $name => $command) {
            ConsoleOutput::primary('php console.php ' . $name);
        }

        ConsoleOutput::info('-----------------------------------');
    }

}
