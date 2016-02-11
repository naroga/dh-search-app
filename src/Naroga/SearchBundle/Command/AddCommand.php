<?php

namespace Naroga\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('search:add')
            ->setDescription('Adds a new entry to the search engine.')
            ->addArgument('path');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('path');

        if ($id = $this->getContainer()->get('naroga.search')->add($filename)) {
            $output->writeln("<info>File written successfully with ID '" . $id . "'!</info>");
        } else {
            $output->writeln("<error>An error ocurred.</error>");
        }
    }
}
