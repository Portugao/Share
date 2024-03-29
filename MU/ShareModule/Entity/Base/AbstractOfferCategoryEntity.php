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
use Zikula\CategoriesModule\Entity\AbstractCategoryAssignment;

/**
 * Entity extension domain class storing offer categories.
 *
 * This is the base category class for offer entities.
 */
abstract class AbstractOfferCategoryEntity extends AbstractCategoryAssignment
{
    /**
     * @ORM\ManyToOne(targetEntity="\MU\ShareModule\Entity\OfferEntity", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var \MU\ShareModule\Entity\OfferEntity
     */
    protected $entity;
    
    /**
     * Get reference to owning entity.
     *
     * @return \MU\ShareModule\Entity\OfferEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Set reference to owning entity.
     *
     * @param \MU\ShareModule\Entity\OfferEntity $entity
     */
    public function setEntity(/*\MU\ShareModule\Entity\OfferEntity */$entity)
    {
        $this->entity = $entity;
    }
}
