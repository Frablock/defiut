<?php

// src/Serializer/CircularReferenceHandler.php
namespace App\Serializer;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        // Retourne l'ID de l'entité pour briser la référence circulaire
        return method_exists($object, 'getId') ? $object->getId() : spl_object_hash($object);
    }
}
