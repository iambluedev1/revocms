<?php
namespace Shell;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClearLogsCommand
 * @package Shell
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class ClearLogsCommand extends Command {

    /**
     * Configure Method
     */

    protected function configure(){
        $this
        ->setName('clear:logs')
        ->setDescription('Clear logs')
        ->setHelp('This command allows you to clear logs folder');
    }

    /**
     * Execute Method
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */

    protected function execute(InputInterface $input, OutputInterface $output){
       $output->writeln('Whoa!');
    }
}


