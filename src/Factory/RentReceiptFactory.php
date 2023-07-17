<?php

namespace App\Factory;

use App\Entity\RentReceipt;
use App\Repository\RentReceiptRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RentReceipt>
 *
 * @method        RentReceipt|Proxy create(array|callable $attributes = [])
 * @method static RentReceipt|Proxy createOne(array $attributes = [])
 * @method static RentReceipt|Proxy find(object|array|mixed $criteria)
 * @method static RentReceipt|Proxy findOrCreate(array $attributes)
 * @method static RentReceipt|Proxy first(string $sortedField = 'id')
 * @method static RentReceipt|Proxy last(string $sortedField = 'id')
 * @method static RentReceipt|Proxy random(array $attributes = [])
 * @method static RentReceipt|Proxy randomOrCreate(array $attributes = [])
 * @method static RentReceiptRepository|RepositoryProxy repository()
 * @method static RentReceipt[]|Proxy[] all()
 * @method static RentReceipt[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static RentReceipt[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static RentReceipt[]|Proxy[] findBy(array $attributes)
 * @method static RentReceipt[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RentReceipt[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RentReceiptFactory extends ModelFactory
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
            'apartment' => ApartmentFactory::new(),
            'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'month' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RentReceipt $rentReceipt): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RentReceipt::class;
    }
}
