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

namespace MU\ShareModule\Entity;

use MU\ShareModule\Entity\Base\AbstractLocationEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for location entities.
 * @ORM\Entity(repositoryClass="MU\ShareModule\Entity\Repository\LocationRepository")
 * @ORM\Table(name="mu_share_location",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class LocationEntity extends BaseEntity
{
    // feel free to add your own methods here
}
