<?php
namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Building;
use Doctrine\ORM\QueryBuilder;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    public function __construct( private Security $security, private AuthorizationCheckerInterface $authorizationChecker){

    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass):void{
        $user = $this->security->getUser();

        if(
            ($resourceClass === Building::class )
            && !$this->authorizationChecker->isGranted('ROLE_ADMIN')
            && $user instanceof User
        ){
            $rootAlias = $queryBuilder->getRootAliases()[0];

            $queryBuilder->join("$rootAlias.syndicate", "s")
            ->join("s.users", "u")

                ->andWhere("u.id = :user");
            $queryBuilder->setParameter("user", $user);
        }

    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []):void{
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []):void{
        $this->addWhere($queryBuilder, $resourceClass);
    }
}