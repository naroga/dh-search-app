<?php

namespace Naroga\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SearchCommand
 * @package Naroga\SearchBundle\Command
 */
class SearchCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('search:search')
            ->setDescription('Searches for an expression')
            ->addArgument('expression');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $expression = $input->getArgument('expression');
        $result = $this->getContainer()->get('naroga.search')->search($expression);
        $output->writeln('<info>Found ' . count($result) . ' results.</info>');
        foreach ($result as $data) {
            $output->writeln($data['file']->getName() . ' with a relevance of ' . $data['score'] . '.');
        }
    }
}
