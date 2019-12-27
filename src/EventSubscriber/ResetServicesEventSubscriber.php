<?php

declare(strict_types=1);

/*
 * This file is part of the SculpinWebpackEncoreBundle package.
 *
 * (c) Niels Nijens <nijens.niels@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nijens\SculpinWebpackEncoreBundle\EventSubscriber;

use Sculpin\Core\Event\FormatEvent;
use Sculpin\Core\Sculpin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Resets services implementing the {@see ResetInterface} on the {@see Sculpin::EVENT_BEFORE_FORMAT} event.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class ResetServicesEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ResetInterface[]
     */
    private $resetableServices;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Sculpin::EVENT_BEFORE_FORMAT => 'resetServices',
        ];
    }

    /**
     * Constructs a new ResetServicesEventSubscriber instance.
     *
     * @param ResetInterface[] $resetableServices
     */
    public function __construct($resetableServices)
    {
        $this->resetableServices = $resetableServices;
    }

    public function resetServices(FormatEvent $event): void
    {
        foreach ($this->resetableServices as $resetableService) {
            $resetableService->reset();
        }
    }
}
