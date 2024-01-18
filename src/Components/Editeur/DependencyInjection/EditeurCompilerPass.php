<?php

namespace App\Components\Editeur\DependencyInjection;

use App\Components\Editeur\EditeurRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class EditeurCompilerPass implements CompilerPassInterface
{
    /*
    * You can modify the container here before it is dumped to PHP code.
    */
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->getDefinition(EditeurRegistry::class);
        $this->addToRegistry($container, $registry, EditeurRegistry::TAG_TYPE_ELEMENT, 'registerElement');
    }

    private function addToRegistry(ContainerBuilder $container, Definition $registry, string $tag, string $method): void
    {
        $taggedServices = $container->findTaggedServiceIds($tag);

        foreach ($taggedServices as $id => $tags) {
            $registry->addMethodCall($method, [$id, new Reference($id)]);
        }
    }
}