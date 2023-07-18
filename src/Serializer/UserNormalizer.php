<?php


namespace App\Serializer;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;
final class UserNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'APPUserNormalizerAlreadyCalled';

    public function  __construct( private StorageInterface $storage)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof User;
        // TODO: Implement supportsNormalization() method.
    }
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $object->setPicture($this->storage->resolveUri($object, 'file' ));
        $context[self::ALREADY_CALLED] =true;
        return $this->normalizer->normalize($object, $format, $context);
    }





}