<?php
namespace App\Command;

use App\Entity\Coordinator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $existing = $this->em->getRepository(Coordinator::class)->findOneBy(['email' => 'admin@voluntrack.com']);
        if ($existing) {
            $output->writeln('Admin already exists');
            return Command::SUCCESS;
        }

        $coordinator = new Coordinator();
        $coordinator->setName('Admin');
        $coordinator->setEmail('admin@voluntrack.com');
        $coordinator->setPassword($this->hasher->hashPassword($coordinator, 'admin1234'));

        $this->em->persist($coordinator);
        $this->em->flush();

        $output->writeln('Admin created successfully');
        return Command::SUCCESS;
    }
}
