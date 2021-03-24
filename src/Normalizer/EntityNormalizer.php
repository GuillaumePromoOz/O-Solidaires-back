<?php

// src\Normalizer\EntityNormalizer.php

namespace App\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Entity normalizer
 */
class EntityNormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface **/
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Doit-on appeler la méthode denormalize ?
     * 
     * Oui, si l'objet fourni est de type App\Entity et que la donnée fournie est un entier
     * $data = l'id du genre dans le JSON
     * $type = le FQCN de la classe
     * 
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data));
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // Va chercher l'entité qui correspond à l'id fourni
        // Doc : This is just a convenient shortcut for getRepository($className)->find($id)
        return $this->em->find($class, $data);
    }
}