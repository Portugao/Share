<?php
/**
 * Share.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\ShareModule\Entity\Factory\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use MU\ShareModule\Entity\Factory\EntityInitialiser;
use MU\ShareModule\Helper\CollectionFilterHelper;

/**
 * Factory class used to create entities and receive entity repositories.
 */
abstract class AbstractEntityFactory
{
    /**
     * @var ObjectManager The object manager to be used for determining the repository
     */
    protected $objectManager;

    /**
     * @var EntityInitialiser The entity initialiser for dynamical application of default values
     */
    protected $entityInitialiser;

    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;

    /**
     * EntityFactory constructor.
     *
     * @param ObjectManager          $objectManager          The object manager to be used for determining the repositories
     * @param EntityInitialiser      $entityInitialiser      The entity initialiser for dynamical application of default values
     * @param CollectionFilterHelper $collectionFilterHelper CollectionFilterHelper service instance
     */
    public function __construct(
        ObjectManager $objectManager,
        EntityInitialiser $entityInitialiser,
        CollectionFilterHelper $collectionFilterHelper)
    {
        $this->objectManager = $objectManager;
        $this->entityInitialiser = $entityInitialiser;
        $this->collectionFilterHelper = $collectionFilterHelper;
    }

    /**
     * Returns a repository for a given object type.
     *
     * @param string $objectType Name of desired entity type
     *
     * @return EntityRepository The repository responsible for the given object type
     */
    public function getRepository($objectType)
    {
        $entityClass = 'MU\\ShareModule\\Entity\\' . ucfirst($objectType) . 'Entity';

        $repository = $this->objectManager->getRepository($entityClass);
        $repository->setCollectionFilterHelper($this->collectionFilterHelper);

        return $repository;
    }

    /**
     * Creates a new location instance.
     *
     * @return MU\ShareModule\Entity\locationEntity The newly created entity instance
     */
    public function createLocation()
    {
        $entityClass = 'MU\\ShareModule\\Entity\\LocationEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initLocation($entity);

        return $entity;
    }

    /**
     * Creates a new offer instance.
     *
     * @return MU\ShareModule\Entity\offerEntity The newly created entity instance
     */
    public function createOffer()
    {
        $entityClass = 'MU\\ShareModule\\Entity\\OfferEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initOffer($entity);

        return $entity;
    }

    /**
     * Creates a new pool instance.
     *
     * @return MU\ShareModule\Entity\poolEntity The newly created entity instance
     */
    public function createPool()
    {
        $entityClass = 'MU\\ShareModule\\Entity\\PoolEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initPool($entity);

        return $entity;
    }

    /**
     * Creates a new message instance.
     *
     * @return MU\ShareModule\Entity\messageEntity The newly created entity instance
     */
    public function createMessage()
    {
        $entityClass = 'MU\\ShareModule\\Entity\\MessageEntity';

        $entity = new $entityClass();

        $this->entityInitialiser->initMessage($entity);

        return $entity;
    }

    /**
     * Returns the identifier field's name for a given object type.
     *
     * @param string $objectType The object type to be treated
     *
     * @return string Primary identifier field name
     */
    public function getIdField($objectType = '')
    {
        if (empty($objectType)) {
            throw new InvalidArgumentException('Invalid object type received.');
        }
        $entityClass = 'MUShareModule:' . ucfirst($objectType) . 'Entity';
    
        $meta = $this->getObjectManager()->getClassMetadata($entityClass);
    
        return $meta->getSingleIdentifierFieldName();
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Sets the object manager.
     *
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function setObjectManager($objectManager)
    {
        if ($this->objectManager != $objectManager) {
            $this->objectManager = $objectManager;
        }
    }
    

    /**
     * Returns the entity initialiser.
     *
     * @return EntityInitialiser
     */
    public function getEntityInitialiser()
    {
        return $this->entityInitialiser;
    }
    
    /**
     * Sets the entity initialiser.
     *
     * @param EntityInitialiser $entityInitialiser
     *
     * @return void
     */
    public function setEntityInitialiser($entityInitialiser)
    {
        if ($this->entityInitialiser != $entityInitialiser) {
            $this->entityInitialiser = $entityInitialiser;
        }
    }
    
}
