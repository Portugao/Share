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

namespace MU\ShareModule\Helper;

use MU\ShareModule\Helper\Base\AbstractWorkflowHelper;
use Zikula\Core\Doctrine\EntityAccess;
use MU\ShareModule\Entity\LocationEntity;
use MU\ShareModule\Entity\MessageEntity;
use MU\ShareModule\Entity\OfferEntity;

use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\Registry;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use MU\ShareModule\Entity\Factory\EntityFactory;
use MU\ShareModule\Helper\ListEntriesHelper;

use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;
use Zikula\MailerModule\Api\ApiInterface\MailerApiInterface;
use Swift_Message;

/**
 * Helper implementation class for workflow methods.
 */
class WorkflowHelper extends AbstractWorkflowHelper
{
	/**
	 * @var MailerApiInterface
	 */
	protected $mailerApi;
	
	/**
	 * @var CurrentUserApiInterface
	 */
	protected $currentUserApi;
	
	/**
	 * @var UserRepositoryInterface
	 */
	protected $userRepository;
	
	/**
	 * WorkflowHelper constructor.
	 *
	 * @param TranslatorInterface     $translator        Translator service instance
	 * @param Registry                $registry          Workflow registry service instance
	 * @param LoggerInterface         $logger            Logger service instance
	 * @param PermissionApiInterface  $permissionApi     PermissionApi service instance
	 * @param CurrentUserApiInterface $currentUserApi    CurrentUserApi service instance
	 * @param EntityFactory           $entityFactory     EntityFactory service instance
	 * @param ListEntriesHelper       $listEntriesHelper ListEntriesHelper service instance
     * @param MailerApiInterface        $mailerApi           MailerApi service instance
	 * @param UserRepositoryInterface   $userRepository   UserRepositoryInterface service instance
	 *
	 * @return void
	 */
	public function __construct(
			TranslatorInterface $translator,
			Registry $registry,
			LoggerInterface $logger,
			PermissionApiInterface $permissionApi,
			CurrentUserApiInterface $currentUserApi,
			EntityFactory $entityFactory,
			ListEntriesHelper $listEntriesHelper,
            MailerApiInterface $mailerApi,
			UserRepositoryInterface $userRepository
			) {
				$this->translator = $translator;
				$this->workflowRegistry = $registry;
				$this->logger = $logger;
				$this->permissionApi = $permissionApi;
				$this->currentUserApi = $currentUserApi;
				$this->entityFactory = $entityFactory;
				$this->listEntriesHelper = $listEntriesHelper;
                $this->mailerApi = $mailerApi;
                $this->userRepository = $userRepository;
	}
    
    /**
     * Executes a certain workflow action for a given entity object.
     *
     * @param EntityAccess $entity    The given entity instance
     * @param string       $actionId  Name of action to be executed
     * @param bool         $recursive True if the function called itself
     *
     * @return bool False on error or true if everything worked well
     */
    public function executeAction(EntityAccess $entity, $actionId = '', $recursive = false)
    {
    	$workflow = $this->workflowRegistry->get($entity);
    	if (!$workflow->can($entity, $actionId)) {
    		return false;
    	}
    
    	// get entity manager
    	$entityManager = $this->entityFactory->getObjectManager();
    	$logArgs = ['app' => 'MUShareModule', 'user' => $this->currentUserApi->get('uname')];
    
    	$result = false;
    	
    	$uid = $this->currentUserApi->get('uid');
    	
    	$locationRepository = $this->entityFactory->getRepository('location');
    	$offerRepository = $this->entityFactory->getRepository('offer');
    	$poolRespository = $this->entityFactory->getRepository('pool');
    
    	try {
    		$workflow->apply($entity, $actionId);
    		if ($actionId == 'delete') {
    			// if the entity is offer
    			if ($entity instanceof OfferEntity) {
    		        $offerPool = $entity['pool'];
    		        if ($offerPool != NULL) {   		        	
    		        	$thisPool = $poolRespository->find($offerPool['id']);
    		        	if (count($thisPool['offers']) > 1) {
    		        		foreach ($thisPool['offers'] as $offer) {
    		        			$thisOffer = $offerRepository->find($offer['id']);
    		        			$thisOffer->setPool(NULL);
    		        			$entityManager->flush();
    		        		}
    		        		$entityManager->remove($entity);
    		        		$entityManager->remove($thisPool);
    		        		$entityManager->flush();
    		        	}	        	
    		        }
    			// if the entity is message	
    			} elseif ($entity instanceof MessageEntity) {
    				if ($entity['statusSender'] == 1 && $entity['statusRecipient'] == 1) {
    					if ($uid == $entity->getCreatedBy()->getUid()) {
    					    $entity->setStatusSender(2);
    					} else {
    						$entity->setStatusRecipient(2);
    					}
    					$entity->setWorkflowState('approved');
    					$entityManager->flush();
    				} elseif ($entity['statusSender'] == 1 && $entity['statusRecipient'] == 2) {
    					if ($uid == $entity->getCreatedBy()->getUid()) {
    						$entityManager->remove($entity);
    					}  					
    				} elseif ($entity['statusSender'] == 2 && $entity['statusRecipient'] == 1) {
    					if ($uid != $entity->getCreatedBy()->getUid()) {
    						$entityManager->remove($entity);
    					}    					
    				}
    			} elseif ($entity instanceof LocationEntity) {
    				// we set another loction for the map
    				$where = 'tbl.id != ' . $entity['id'];
    				$where .= ' AND ';
    				$where .= 'tbl.createdBy = ' . $entity->getCreatedBy()->getUid();
    				$locations = $locationRepository->selectWhere($where);
    				if (count($locations) == 0) {
    					//die('T'); // TODO
    					
    				} else {
    					$thisLocation = $locationRepository->find($locations[0]['id']);
    					$thisLocation->setForMap(1);
    					$entityManager->flush();
    				}
    				$entityManager->remove($entity);
    				$entityManager->flush();
    				//
    			} else {
    				$entityManager->remove($entity);
    			}
    		} elseif ($actionId == 'submit') {
    			$entityManager->persist($entity);
    			if ($entity instanceof LocationEntity) {
    				
    			} elseif ($entity instanceof MessageEntity) {
    				$creatorId = $entity->getCreatedBy()->getUid();
    				$user = $this->userRepository->find($creatorId);
    				$message = Swift_Message::newInstance();
    				$message->setFrom('info@papershare.de');
    				$message->setTo($user['email']);
    				$message->setBody($entity['textFormessage'] , 'text/html');
    				$this->mailerApi->sendMessage($message, $this->translator->__('New message from papershare.de') . ' - ' . $entity['subject'], $entity['text'], $entity['text']);
    			}
    		} elseif ($actionId == 'updateapproved') {
    	          if ($entity instanceof OfferEntity) {
    				if ($entity['pool'] != NULL) {
    					$thisPool = $poolRespository->find($entity['pool']['id']);
    					// where clause for getting other offers in pool
    					$where = 'tbl.pool = ' . $thisPool['id'];
    					$where .= ' AND ';
    					$where .= 'tbl.id != ' . $entity['id'];    			
    					// we get other offers
    					$poolOffers = $offerRepository->selectWhere($where);
    					$poolLatitude = $poolOffers[0]['latitude'];
    					$poolLongitude = $poolOffers[0]['longitude'];

    					// if the geo datas are set to different
    					// we set pool to null for this entry
    				    if ($entity['latitude'] != $poolLatitude || $entity['longitude'] != $poolLongitude) {
    				    	$entity->setPool(NULL);
    				    	$entityManager->flush();
    				    }
    				    // if one other offer in pool we set aslo the pool to NULL
    				    // and delete the pool
     				    if (count($poolOffers) == 1) {
     				    	$thisPoolOffer = $offerRepository->find($poolOffers[0]['id']);
     				    	$thisPoolOffer->setPool(NULL);
     				    	$entityManager->flush();
     				    	$entityManager->remove($thisPool);
     				    	$entityManager->flush();
     				    }
    				}
    		}
    		}
    		$entityManager->flush();
    
    		$result = true;
    		if ($actionId == 'delete') {
    			$this->logger->notice('{app}: User {user} deleted an entity.', $logArgs);
    		} else {
    			$this->logger->notice('{app}: User {user} updated an entity.', $logArgs);
    		}
    	} catch (\Exception $exception) {
    		if ($actionId == 'delete') {
    			$this->logger->error('{app}: User {user} tried to delete an entity, but failed.', $logArgs);
    		} else {
    			$this->logger->error('{app}: User {user} tried to update an entity, but failed.', $logArgs);
    		}
    		throw new \RuntimeException($exception->getMessage());
    	}
    
    	if (false !== $result && !$recursive) {
    		$entities = $entity->getRelatedObjectsToPersist();
    		foreach ($entities as $rel) {
    			if ($rel->getWorkflowState() == 'initial') {
    				$this->executeAction($rel, $actionId, true);
    			}
    		}
    	}
    
    	return (false !== $result);
    }
}
