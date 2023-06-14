<?php

namespace App\Factory;

use App\Entity\Apartment;
use App\Repository\ApartmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Apartment>
 *
 * @method        Apartment|Proxy create(array|callable $attributes = [])
 * @method static Apartment|Proxy createOne(array $attributes = [])
 * @method static Apartment|Proxy find(object|array|mixed $criteria)
 * @method static Apartment|Proxy findOrCreate(array $attributes)
 * @method static Apartment|Proxy first(string $sortedField = 'id')
 * @method static Apartment|Proxy last(string $sortedField = 'id')
 * @method static Apartment|Proxy random(array $attributes = [])
 * @method static Apartment|Proxy randomOrCreate(array $attributes = [])
 * @method static ApartmentRepository|RepositoryProxy repository()
 * @method static Apartment[]|Proxy[] all()
 * @method static Apartment[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Apartment[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Apartment[]|Proxy[] findBy(array $attributes)
 * @method static Apartment[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Apartment[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ApartmentFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'building' => BuildingFactory::new(),
            'floor' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Apartment $apartment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Apartment::class;
    }
}
