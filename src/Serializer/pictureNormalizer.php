<?php


namespace App\Serializer;
use App\Entity\AdvertisementPicture;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
final class pictureNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'APPUserNormalizerAlreadyCalled';

    public function  __construct( private StorageInterface $storage, private RequestStack $requestStack)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof AdvertisementPicture;
        // TODO: Implement supportsNormalization() method.
    }
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        // Check if already normalized
        if (isset($context[self::ALREADY_CALLED])) {
            return $this->normalizer->normalize($object, $format, $context);
        }
        $context[self::ALREADY_CALLED] = true;

        // Normalize the object without modifying it
        $normalizedData = $this->normalizer->normalize($object, $format, $context);
        // Update the "picture" URL
        if (isset($normalizedData['file'])) {
            $fileUrl = $this->storage->resolveUri($object, 'urlFile');
            $request = $this->requestStack->getCurrentRequest();
            $baseUrl = $request->getSchemeAndHttpHost();
            $normalizedData['file'] = $baseUrl . $fileUrl;
        }

        return $normalizedData;
    }





}