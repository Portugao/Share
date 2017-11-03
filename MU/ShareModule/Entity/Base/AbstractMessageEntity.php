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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\UsersModule\Entity\UserEntity;
use MU\ShareModule\Traits\StandardFieldsTrait;
use MU\ShareModule\Validator\Constraints as ShareAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for message entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractMessageEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'message';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @ShareAssert\ListEntry(entityName="message", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * Enter a subject for your message!
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $subject
     */
    protected $subject = '';
    
    /**
     * Enter your text for your message.
     * @ORM\Column(type="text", length=4000)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="4000")
     * @var text $textForMessage
     */
    protected $textForMessage = '';
    
    /**
     * @ORM\ManyToOne(targetEntity="Zikula\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(referencedColumnName="uid")
     * @Assert\NotBlank()
     * @var UserEntity $recipient
     */
    protected $recipient = null;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     * @Assert\DateTime()
     * @var DateTime $readByRecipient
     */
    protected $readByRecipient;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=100000000000)
     * @var integer $statusSender
     */
    protected $statusSender = 1;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=100000000000)
     * @var integer $statusRecipient
     */
    protected $statusRecipient = 1;
    
    
    
    /**
     * MessageEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
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
     * Returns the subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * Sets the subject.
     *
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        if ($this->subject !== $subject) {
            $this->subject = isset($subject) ? $subject : '';
        }
    }
    
    /**
     * Returns the text for message.
     *
     * @return text
     */
    public function getTextForMessage()
    {
        return $this->textForMessage;
    }
    
    /**
     * Sets the text for message.
     *
     * @param text $textForMessage
     *
     * @return void
     */
    public function setTextForMessage($textForMessage)
    {
        if ($this->textForMessage !== $textForMessage) {
            $this->textForMessage = isset($textForMessage) ? $textForMessage : '';
        }
    }
    
    /**
     * Returns the recipient.
     *
     * @return UserEntity
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
    
    /**
     * Sets the recipient.
     *
     * @param UserEntity $recipient
     *
     * @return void
     */
    public function setRecipient($recipient)
    {
        if ($this->recipient !== $recipient) {
            $this->recipient = isset($recipient) ? $recipient : '';
        }
    }
    
    /**
     * Returns the read by recipient.
     *
     * @return DateTime
     */
    public function getReadByRecipient()
    {
        return $this->readByRecipient;
    }
    
    /**
     * Sets the read by recipient.
     *
     * @param DateTime $readByRecipient
     *
     * @return void
     */
    public function setReadByRecipient($readByRecipient)
    {
        if ($this->readByRecipient !== $readByRecipient) {
            if (is_object($readByRecipient) && $readByRecipient instanceOf \DateTime) {
                $this->readByRecipient = $readByRecipient;
            } else {
                $this->readByRecipient = new \DateTime($readByRecipient);
            }
        }
    }
    
    /**
     * Returns the status sender.
     *
     * @return integer
     */
    public function getStatusSender()
    {
        return $this->statusSender;
    }
    
    /**
     * Sets the status sender.
     *
     * @param integer $statusSender
     *
     * @return void
     */
    public function setStatusSender($statusSender)
    {
        if (intval($this->statusSender) !== intval($statusSender)) {
            $this->statusSender = intval($statusSender);
        }
    }
    
    /**
     * Returns the status recipient.
     *
     * @return integer
     */
    public function getStatusRecipient()
    {
        return $this->statusRecipient;
    }
    
    /**
     * Sets the status recipient.
     *
     * @param integer $statusRecipient
     *
     * @return void
     */
    public function setStatusRecipient($statusRecipient)
    {
        if (intval($this->statusRecipient) !== intval($statusRecipient)) {
            $this->statusRecipient = intval($statusRecipient);
        }
    }
    
    
    
    
    /**
     * Checks whether the recipient field contains a valid user reference.
     * This method is used for validation.
     *
     * @Assert\IsTrue(message="This value must be a valid user id.")
     *
     * @return boolean True if data is valid else false
     */
    public function isRecipientUserValid()
    {
        return $this['recipient'] instanceof UserEntity;
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
        return 'musharemodule.ui_hooks.messages';
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
        return 'Message ' . $this->getKey() . ': ' . $this->getSubject();
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
