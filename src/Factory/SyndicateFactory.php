<?php

namespace App\Factory;

use App\Entity\Syndicate;
use App\Repository\SyndicateRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Syndicate>
 *
 * @method        Syndicate|Proxy create(array|callable $attributes = [])
 * @method static Syndicate|Proxy createOne(array $attributes = [])
 * @method static Syndicate|Proxy find(object|array|mixed $criteria)
 * @method static Syndicate|Proxy findOrCreate(array $attributes)
 * @method static Syndicate|Proxy first(string $sortedField = 'id')
 * @method static Syndicate|Proxy last(string $sortedField = 'id')
 * @method static Syndicate|Proxy random(array $attributes = [])
 * @method static Syndicate|Proxy randomOrCreate(array $attributes = [])
 * @method static SyndicateRepository|RepositoryProxy repository()
 * @method static Syndicate[]|Proxy[] all()
 * @method static Syndicate[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Syndicate[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Syndicate[]|Proxy[] findBy(array $attributes)
 * @method static Syndicate[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Syndicate[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class SyndicateFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
            'street' => self::faker()->text(255),
            'street_number' => self::faker()->randomNumber(),
            'type' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Syndicate $syndicate): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Syndicate::class;
    }
}
