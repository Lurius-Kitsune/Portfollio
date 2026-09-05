<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gedmo\Translatable\TranslatableListener;

#[AsCommand(name: 'app:tailwind:extract-classes', description: 'Extrait les classes CSS du contenu HTML stocké en DB pour Tailwind')]
class ExtractTailwindClassesCommand extends Command
{


    public function __construct(
        private ProjectRepository $projectRepository,
        private TranslatableListener $translatableListener,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->translatableListener->setTranslatableLocale('fr'); // adapte à ta locale réelle
        $classes = [];
        foreach ($this->projectRepository->findAll() as $project) {
            dump($project->getContent());
            preg_match_all('/class=["\']([^"\']+)["\']/i', $project->getContent(), $matches);

            foreach ($matches[1] as $classString) {
                foreach (preg_split('/\s+/', trim($classString)) as $class) {
                    if ($class !== '') {
                        $classes[$class] = true;
                    }
                }
            }
        }

        $path = __DIR__ . '/../../assets/tailwind-content-classes.txt';
        file_put_contents($path, implode(' ', array_keys($classes)));

        $output->writeln(sprintf('%d classes extraites -> %s', count($classes), $path));

        return Command::SUCCESS;
    }
}
