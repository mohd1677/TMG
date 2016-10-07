<?php

namespace TMG\Console\CommandBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base Parser
 *
 * Contains base resources and functionality used in multiple parsers.
 */
class BaseParser
{
    /** @var \Symfony\Component\Console\Output\OutputInterface Holds an instance of the output interface */
    protected $output;

    /** @var \Doctrine\ORM\EntityManager Holds an instance of Doctrine Entity Manager */
    protected $entityManager;

    /**
     * BaseParser constructor.
     *
     * @param OutputInterface         $output
     * @param EntityManager           $entityManager
     * @param ContainerInterface|null $container
     */
    public function __construct(
        OutputInterface $output,
        EntityManager $entityManager,
        ContainerInterface $container = null
    ) {
        // Store the output interface where we can access it from other methods.
        $this->output = $output;

        // Store the entity manager where we can access it from other methods.
        $this->entityManager = $entityManager;

        // Store the container where we can access it from other methods.
        $this->container = $container;
    }
}
