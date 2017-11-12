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

namespace MU\ShareModule\Form\Type;

use MU\ShareModule\Form\Type\Base\AbstractOfferType;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Common\Translator\TranslatorInterface;

use MU\ShareModule\Entity\Factory\EntityFactory;
use MU\ShareModule\Helper\CollectionFilterHelper;
use MU\ShareModule\Helper\EntityDisplayHelper;
use MU\ShareModule\Helper\FeatureActivationHelper;
use MU\ShareModule\Helper\ListEntriesHelper;

/**
 * Offer editing form type implementation class.
 */
class OfferType extends AbstractOfferType
{
	/**
	 * @var CurrentUserApiInterface
	 */
	protected $currentUserApi;	
	
	/**
	 * OfferType constructor.
	 *
	 * @param TranslatorInterface $translator    Translator service instance
	 * @param EntityFactory $entityFactory EntityFactory service instance
	 * @param CollectionFilterHelper $collectionFilterHelper CollectionFilterHelper service instance
	 * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
	 * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
	 * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
	 * @param CurrentUserApiInterface $currentUserApi  CurrentUserApi service instance
	 */
	public function __construct(
			TranslatorInterface $translator,
			EntityFactory $entityFactory,
			CollectionFilterHelper $collectionFilterHelper,
			EntityDisplayHelper $entityDisplayHelper,
			ListEntriesHelper $listHelper,
			FeatureActivationHelper $featureActivationHelper,
			CurrentUserApiInterface $currentUserApi
			) {
				$this->setTranslator($translator);
				$this->entityFactory = $entityFactory;
				$this->collectionFilterHelper = $collectionFilterHelper;
				$this->entityDisplayHelper = $entityDisplayHelper;
				$this->listHelper = $listHelper;
				$this->featureActivationHelper = $featureActivationHelper;
				$this->currentUserApi = $currentUserApi;
	}
	
    /**
     * Adds fields for incoming relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIncomingRelationshipFields(FormBuilderInterface $builder, array $options)
    {
    	

        $queryBuilder = function(EntityRepository $er) {
    		// select without joins
        	$uid = $this->currentUserApi->get('uid');
        	if ($uid == 2) {
        		$where = '';
        	} else {
        		$where = 'tbl.createdBy = ' . $uid;
        	}
    		return $er->getListQueryBuilder($where, '', false);
    	};    		

        $entityDisplayHelper = $this->entityDisplayHelper;
        $choiceLabelClosure = function ($entity) use ($entityDisplayHelper) {
            return $entityDisplayHelper->getFormattedTitle($entity);
        };
        $builder->add('locationOfOffer', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'MUShareModule:LocationEntity',
            'choice_label' => $choiceLabelClosure,
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'label' => $this->__('Location of offer'),
            'attr' => [
                'title' => $this->__('Choose your location of offer')
            ]
        ]);
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $entityDisplayHelper = $this->entityDisplayHelper;
        $choiceLabelClosure = function ($entity) use ($entityDisplayHelper) {
            return $entityDisplayHelper->getFormattedTitle($entity);
        };
        $builder->add('pool', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'MUShareModule:PoolEntity',
            'choice_label' => $choiceLabelClosure,
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'placeholder' => $this->__('Please choose an option'),
            'required' => false,
            'label' => $this->__('Pool'),
            'attr' => [
                'title' => $this->__('Choose the pool')
            ]
        ]);
    }
}
