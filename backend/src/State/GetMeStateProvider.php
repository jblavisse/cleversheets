<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @template T of object
 * @implements ProviderInterface<T>
 */
class GetMeStateProvider extends AbstractController implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->getUser();
    }
}