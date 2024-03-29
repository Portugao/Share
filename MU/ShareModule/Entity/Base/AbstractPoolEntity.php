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

namespace MU\ShareModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use MU\ShareModule\Traits\StandardFieldsTrait;
use MU\ShareModule\Validator\Constraints as ShareAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for pool entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractPoolEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'pool';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @ShareAssert\ListEntry(entityName="pool", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $collectionOfPool
     */
    protected $collectionOfPool = 'collection';
    
    
    /**
     * Bidirectional - One pool [pool] has many offers [offers] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="MU\ShareModule\Entity\OfferEntity", mappedBy="pool")
     * @ORM\JoinTable(name="mu_share_pooloffers")
     * @var \MU\ShareModule\Entity\OfferEntity[] $offers
     */
    protected $offers = null;
    
    
    /**
     * PoolEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the collection of pool.
     *
     * @return string
     */
    public function getCollectionOfPool()
    {
        return $this->collectionOfPool;
    }
    
    /**
     * Sets the collection of pool.
     *
     * @param string $collectionOfPool
     *
     * @return void
     */
    public function setCollectionOfPool($collectionOfPool)
    {
        if ($this->collectionOfPool !== $collectionOfPool) {
            $this->collectionOfPool = isset($collectionOfPool) ? $collectionOfPool : '';
        }
    }
    
    
    /**
     * Returns the offers.
     *
     * @return \MU\ShareModule\Entity\OfferEntity[]
     */
    public function getOffers()
    {
        return $this->offers;
    }
    
    /**
     * Sets the offers.
     *
     * @param \MU\ShareModule\Entity\OfferEntity[] $offers
     *
     * @return void
     */
    public function setOffers($offers)
    {
        foreach ($this->offers as $offerSingle) {
            $this->removeOffers($offerSingle);
        }
        foreach ($offers as $offerSingle) {
            $this->addOffers($offerSingle);
        }
    }
    
    /**
     * Adds an instance of \MU\ShareModule\Entity\OfferEntity to the list of offers.
     *
     * @param \MU\ShareModule\Entity\OfferEntity $offer The instance to be added to the collection
     *
     * @return void
     */
    public function addOffers(\MU\ShareModule\Entity\OfferEntity $offer)
    {
        $this->offers->add($offer);
        $offer->setPool($this);
    }
    
    /**
     * Removes an instance of \MU\ShareModule\Entity\OfferEntity from the list of offers.
     *
     * @param \MU\ShareModule\Entity\OfferEntity $offer The instance to be removed from the collection
     *
     * @return void
     */
    public function removeOffers(\MU\ShareModule\Entity\OfferEntity $offer)
    {
        $this->offers->removeElement($offer);
        $offer->setPool(null);
    }
    
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'musharemodule.ui_hooks.pools';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Pool ' . $this->getKey() . ': ' . $this->getCollectionOfPool();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
