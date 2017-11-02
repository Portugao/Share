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

use MU\ShareModule\Entity\LocationEntity;
use MU\ShareModule\Entity\OfferEntity;
use MU\ShareModule\Entity\PoolEntity;
use MU\ShareModule\Entity\MessageEntity;
use MU\ShareModule\Helper\ListEntriesHelper;

/**
 * Entity initialiser class used to dynamically apply default values to newly created entities.
 */
abstract class AbstractEntityInitialiser
{
    /**
     * @var ListEntriesHelper Helper service for managing list entries
     */
    protected $listEntriesHelper;

    /**
     * @var float Default latitude for geographical entities
     */
    protected $defaultLatitude;

    /**
     * @var float Default longitude for geographical entities
     */
    protected $defaultLongitude;

    /**
     * EntityInitialiser constructor.
     *
     * @param ListEntriesHelper $listEntriesHelper Helper service for managing list entries
     * @param float $defaultLatitude Default latitude for geographical entities
     * @param float $defaultLongitude Default longitude for geographical entities
     */
    public function __construct(ListEntriesHelper $listEntriesHelper, $defaultLatitude, $defaultLongitude)
    {
        $this->listEntriesHelper = $listEntriesHelper;
        $this->defaultLatitude = $defaultLatitude;
        $this->defaultLongitude = $defaultLongitude;
    }

    /**
     * Initialises a given location instance.
     *
     * @param LocationEntity $entity The newly created entity instance
     *
     * @return LocationEntity The updated entity instance
     */
    public function initLocation(LocationEntity $entity)
    {

        $entity->setLatitude($this->defaultLatitude);
        $entity->setLongitude($this->defaultLongitude);

        return $entity;
    }

    /**
     * Initialises a given offer instance.
     *
     * @param OfferEntity $entity The newly created entity instance
     *
     * @return OfferEntity The updated entity instance
     */
    public function initOffer(OfferEntity $entity)
    {
        $listEntries = $this->listEntriesHelper->getEntries('offer', 'period');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPeriod(implode('###', $items));


        $entity->setLatitude($this->defaultLatitude);
        $entity->setLongitude($this->defaultLongitude);

        return $entity;
    }

    /**
     * Initialises a given pool instance.
     *
     * @param PoolEntity $entity The newly created entity instance
     *
     * @return PoolEntity The updated entity instance
     */
    public function initPool(PoolEntity $entity)
    {

        return $entity;
    }

    /**
     * Initialises a given message instance.
     *
     * @param MessageEntity $entity The newly created entity instance
     *
     * @return MessageEntity The updated entity instance
     */
    public function initMessage(MessageEntity $entity)
    {

        return $entity;
    }

    /**
     * Returns the list entries helper.
     *
     * @return ListEntriesHelper
     */
    public function getListEntriesHelper()
    {
        return $this->listEntriesHelper;
    }
    
    /**
     * Sets the list entries helper.
     *
     * @param ListEntriesHelper $listEntriesHelper
     *
     * @return void
     */
    public function setListEntriesHelper($listEntriesHelper)
    {
        if ($this->listEntriesHelper != $listEntriesHelper) {
            $this->listEntriesHelper = $listEntriesHelper;
        }
    }
    
}
