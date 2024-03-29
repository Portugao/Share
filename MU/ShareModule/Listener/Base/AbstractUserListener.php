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

namespace MU\ShareModule\Listener\Base;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use Zikula\UsersModule\UserEvents;
use MU\ShareModule\Entity\Factory\EntityFactory;

/**
 * Event handler base class for user-related events.
 */
abstract class AbstractUserListener implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * UserListener constructor.
     *
     * @param TranslatorInterface     $translator     Translator service instance
     * @param EntityFactory           $entityFactory  EntityFactory service instance
     * @param CurrentUserApiInterface $currentUserApi CurrentUserApi service instance
     * @param LoggerInterface         $logger         Logger service instance
     *
     * @return void
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityFactory $entityFactory,
        CurrentUserApiInterface $currentUserApi,
        LoggerInterface $logger
    ) {
        $this->translator = $translator;
        $this->entityFactory = $entityFactory;
        $this->currentUserApi = $currentUserApi;
        $this->logger = $logger;
    }
    
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::CREATE_ACCOUNT => ['create', 5],
            UserEvents::UPDATE_ACCOUNT => ['update', 5],
            UserEvents::DELETE_ACCOUNT => ['delete', 5]
        ];
    }
    
    /**
     * Listener for the `user.account.create` event.
     *
     * Occurs after a user account is created. All handlers are notified.
     * It does not apply to creation of a pending registration.
     * The full user record created is available as the subject.
     * This is a storage-level event, not a UI event. It should not be used for UI-level actions such as redirects.
     * The subject of the event is set to the user record that was created.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function create(GenericEvent $event)
    {
    }
    
    /**
     * Listener for the `user.account.update` event.
     *
     * Occurs after a user is updated. All handlers are notified.
     * The full updated user record is available as the subject.
     * This is a storage-level event, not a UI event. It should not be used for UI-level actions such as redirects.
     * The subject of the event is set to the user record, with the updated values.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function update(GenericEvent $event)
    {
    }
    
    /**
     * Listener for the `user.account.delete` event.
     *
     * Occurs after the deletion of a user account. Subject is $userId.
     * This is a storage-level event, not a UI event. It should not be used for UI-level actions such as redirects.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function delete(GenericEvent $event)
    {
        $userId = $event->getSubject();
    
        
        $repo = $this->entityFactory->getRepository('location');
        // set creator to admin (UsersConstant::USER_ID_ADMIN) for all locations created by this user
        $repo->updateCreator($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        // set last editor to admin (UsersConstant::USER_ID_ADMIN) for all locations updated by this user
        $repo->updateLastEditor($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        $logArgs = ['app' => 'MUShareModule', 'user' => $this->currentUserApi->get('uname'), 'entities' => 'locations'];
        $this->logger->notice('{app}: User {user} has been deleted, so we deleted/updated corresponding {entities}, too.', $logArgs);
        
        $repo = $this->entityFactory->getRepository('offer');
        // set creator to admin (UsersConstant::USER_ID_ADMIN) for all offers created by this user
        $repo->updateCreator($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        // set last editor to admin (UsersConstant::USER_ID_ADMIN) for all offers updated by this user
        $repo->updateLastEditor($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        $logArgs = ['app' => 'MUShareModule', 'user' => $this->currentUserApi->get('uname'), 'entities' => 'offers'];
        $this->logger->notice('{app}: User {user} has been deleted, so we deleted/updated corresponding {entities}, too.', $logArgs);
        
        $repo = $this->entityFactory->getRepository('pool');
        // set creator to admin (UsersConstant::USER_ID_ADMIN) for all pools created by this user
        $repo->updateCreator($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        // set last editor to admin (UsersConstant::USER_ID_ADMIN) for all pools updated by this user
        $repo->updateLastEditor($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        $logArgs = ['app' => 'MUShareModule', 'user' => $this->currentUserApi->get('uname'), 'entities' => 'pools'];
        $this->logger->notice('{app}: User {user} has been deleted, so we deleted/updated corresponding {entities}, too.', $logArgs);
        
        $repo = $this->entityFactory->getRepository('message');
        // set creator to admin (UsersConstant::USER_ID_ADMIN) for all messages created by this user
        $repo->updateCreator($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        
        // set last editor to admin (UsersConstant::USER_ID_ADMIN) for all messages updated by this user
        $repo->updateLastEditor($userId, UsersConstant::USER_ID_ADMIN, $this->translator, $this->logger, $this->currentUserApi);
        // set last editor to guest (UsersConstant::USER_ID_ANONYMOUS) for all messages affected by this user
        $repo->updateUserField('recipient', $userId, UsersConstant::USER_ID_ANONYMOUS, $this->translator, $this->logger, $this->currentUserApi);
        
        $logArgs = ['app' => 'MUShareModule', 'user' => $this->currentUserApi->get('uname'), 'entities' => 'messages'];
        $this->logger->notice('{app}: User {user} has been deleted, so we deleted/updated corresponding {entities}, too.', $logArgs);
    }
}
