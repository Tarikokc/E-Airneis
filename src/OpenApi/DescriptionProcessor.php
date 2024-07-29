<?php
// src/OpenApi/DescriptionProcessor.php

namespace App\OpenApi;

use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareInterface;
use Nelmio\ApiDocBundle\Model\ModelRegistry;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\PathItem;

class DescriptionProcessor implements ModelRegistryAwareInterface
{
    private ModelRegistry $modelRegistry;

    public function setModelRegistry(ModelRegistry $modelRegistry): void
    {
        $this->modelRegistry = $modelRegistry;
    }

    public function __invoke(OpenApi $openApi): void
    {
        foreach ($openApi->paths as $path) {
            if ($path instanceof PathItem) {
                foreach (get_object_vars($path) as $method => $operation) { // get_object_vars($path) remplace $path->getOperations()
                    if ($operation && $operation->description === null) {
                        $operation->description = $operation->summary;
                    }
                }
            }
        }
    }
}
