<?php

namespace Naroga\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 * @package Naroga\SearchBundle\Command
 */
class DeleteCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('search:delete')
            ->setDescription('Deletes an entry')
            ->addArgument('id');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $result = $this->getContainer()->get('naroga.search')->delete($id);
        if ($result === false) {
            $output->writeln('<error>The entry was not found!</error>');
        } else {
            $output->writeln('<info>' . $result . ' entries deleted successfully</info>');
        }
    }
}
