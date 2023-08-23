<?php
// src/YourBundle/Command/SocketCommand.php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\App;
use MyBundle\Sockets\Chat;

class SocketCommand extends Command
{
    protected function configure()
    {
        $this->setName('sockets:start-chat')
            ->setDescription('Starts the chat socket demo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Chat socket',
            '============',
            'Starting chat, open your browser.',
        ]);

        $app = new App('sandbox', 8080, '0.0.0.0');
        $app->route('/chat', new Chat);
        $app->run();
    }
}
