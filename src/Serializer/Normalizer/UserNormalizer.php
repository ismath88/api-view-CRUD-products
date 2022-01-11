<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Domain\Command\User\UpdateEmail;
use App\Domain\Command\User\UpdatePassword;
use App\Entity\User;
use League\Tactician\CommandBus;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait {
        setSerializer as baseSetSerializer;
    }

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var NormalizerInterface
     */
    private $decorated;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param CommandBus                    $commandBus
     * @param NormalizerInterface           $decorated
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, CommandBus $commandBus, NormalizerInterface $decorated)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->commandBus = $commandBus;
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $this->decorated->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (User::class !== $class) {
            return $this->decorated->denormalize($data, $class, $format, $context);
        }

        $object = $this->decorated->denormalize(\array_diff_key($data, [
            'password' => null,
        ]), $class, $format, $context);

        /** @var User $user */
        $user = $object;

        if (isset($data['username'])) {
            $this->commandBus->handle(new UpdateEmail($user, $data['username']));
        }

        if (\array_key_exists('password', $data) || null === $user->getId()) {
            $user->setPlainPassword($data['password'] ?? null);

            $this->commandBus->handle(new UpdatePassword($user, $user->getPlainPassword()));
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->baseSetSerializer($serializer);

        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}
