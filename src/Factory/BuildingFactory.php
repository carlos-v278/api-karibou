<?php

namespace App\Factory;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Building>
 *
 * @method        Building|Proxy create(array|callable $attributes = [])
 * @method static Building|Proxy createOne(array $attributes = [])
 * @method static Building|Proxy find(object|array|mixed $criteria)
 * @method static Building|Proxy findOrCreate(array $attributes)
 * @method static Building|Proxy first(string $sortedField = 'id')
 * @method static Building|Proxy last(string $sortedField = 'id')
 * @method static Building|Proxy random(array $attributes = [])
 * @method static Building|Proxy randomOrCreate(array $attributes = [])
 * @method static BuildingRepository|RepositoryProxy repository()
 * @method static Building[]|Proxy[] all()
 * @method static Building[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Building[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Building[]|Proxy[] findBy(array $attributes)
 * @method static Building[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Building[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class BuildingFactory extends ModelFactory
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
            'city' => self::faker()->text(255),
            'country' => self::faker()->text(255),
            'number' => self::faker()->randomNumber(),
            'street' => self::faker()->text(255),
            'syndicate' => SyndicateFactory::new(),
            'zipcode' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Building $building): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Building::class;
    }
}
