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

namespace MU\ShareModule\Helper\Base;

use Zikula\Common\Translator\TranslatorInterface;
use MU\ShareModule\Entity\LocationEntity;
use MU\ShareModule\Entity\OfferEntity;
use MU\ShareModule\Entity\PoolEntity;
use MU\ShareModule\Entity\CompanyEntity;
use MU\ShareModule\Helper\ListEntriesHelper;

/**
 * Entity display helper base class.
 */
abstract class AbstractEntityDisplayHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ListEntriesHelper Helper service for managing list entries
     */
    protected $listEntriesHelper;

    /**
     * EntityDisplayHelper constructor.
     *
     * @param TranslatorInterface $translator        Translator service instance
     * @param ListEntriesHelper   $listEntriesHelper Helper service for managing list entries
     */
    public function __construct(
        TranslatorInterface $translator,
        ListEntriesHelper $listEntriesHelper
    ) {
        $this->translator = $translator;
        $this->listEntriesHelper = $listEntriesHelper;
    }

    /**
     * Returns the formatted title for a given entity.
     *
     * @param object $entity The given entity instance
     *
     * @return string The formatted title
     */
    public function getFormattedTitle($entity)
    {
        if ($entity instanceof LocationEntity) {
            return $this->formatLocation($entity);
        }
        if ($entity instanceof OfferEntity) {
            return $this->formatOffer($entity);
        }
        if ($entity instanceof PoolEntity) {
            return $this->formatPool($entity);
        }
        if ($entity instanceof CompanyEntity) {
            return $this->formatCompany($entity);
        }
    
        return '';
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param LocationEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatLocation(LocationEntity $entity)
    {
        return $this->translator->__f('%title%', [
            '%title%' => $entity->getTitle()
        ]);
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param OfferEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatOffer(OfferEntity $entity)
    {
        return $this->translator->__f('%product%', [
            '%product%' => $entity->getProduct()
        ]);
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param PoolEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatPool(PoolEntity $entity)
    {
        return $this->translator->__f('%collectionOfPool%', [
            '%collectionOfPool%' => $entity->getCollectionOfPool()
        ]);
    }
    
    /**
     * Returns the formatted title for a given entity.
     *
     * @param CompanyEntity $entity The given entity instance
     *
     * @return string The formatted title
     */
    protected function formatCompany(CompanyEntity $entity)
    {
        return $this->translator->__f('%name%', [
            '%name%' => $entity->getName()
        ]);
    }
    
    /**
     * Returns name of the field used as title / name for entities of this repository.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as title
     */
    public function getTitleFieldName($objectType)
    {
        if ($objectType == 'location') {
            return 'title';
        }
        if ($objectType == 'offer') {
            return 'product';
        }
        if ($objectType == 'pool') {
            return 'collectionOfPool';
        }
        if ($objectType == 'company') {
            return 'name';
        }
    
        return '';
    }
    
    /**
     * Returns name of the field used for describing entities of this repository.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as description
     */
    public function getDescriptionFieldName($objectType)
    {
        if ($objectType == 'location') {
            return 'street';
        }
        if ($objectType == 'offer') {
            return 'description';
        }
        if ($objectType == 'pool') {
            return 'collectionOfPool';
        }
        if ($objectType == 'company') {
            return 'description';
        }
    
        return '';
    }
    
    /**
     * Returns name of the date(time) field to be used for representing the start
     * of this object. Used for providing meta data to the tag module.
     *
     * @param string $objectType Name of treated entity type
     *
     * @return string Name of field to be used as date
     */
    public function getStartDateFieldName($objectType)
    {
        if ($objectType == 'location') {
            return 'createdDate';
        }
        if ($objectType == 'offer') {
            return 'createdDate';
        }
        if ($objectType == 'pool') {
            return 'createdDate';
        }
        if ($objectType == 'company') {
            return 'createdDate';
        }
    
        return '';
    }
}