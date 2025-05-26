<?php

namespace App\Command;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-redis-cache',
    description: 'Add a short description for your command',
)]
class TestRedisCacheCommand extends Command
{
    public function __construct(private readonly CacheItemPoolInterface $cachePool)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $item = $this->cachePool->getItem('symfony.test');

        if (!$item->isHit()) {
          $item->set('Hello from Symfony');
          $item->expiresAfter(60); // 60s
          $this->cachePool->save($item);
        } else {
          $output->writeln('Cache hit, value: ' . $item->get() . '. ');
        }

        return Command::SUCCESS;
    }
}
