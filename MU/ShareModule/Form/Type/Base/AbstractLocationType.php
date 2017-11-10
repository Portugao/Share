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

namespace MU\ShareModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use MU\ShareModule\Entity\Factory\EntityFactory;
use MU\ShareModule\Form\Type\Field\GeoType;
use Zikula\UsersModule\Form\Type\UserLiveSearchType;
use MU\ShareModule\Helper\CollectionFilterHelper;
use MU\ShareModule\Helper\EntityDisplayHelper;
use MU\ShareModule\Helper\FeatureActivationHelper;
use MU\ShareModule\Helper\ListEntriesHelper;

/**
 * Location editing form type base class.
 */
abstract class AbstractLocationType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;

    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * LocationType constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param EntityFactory $entityFactory EntityFactory service instance
     * @param CollectionFilterHelper $collectionFilterHelper CollectionFilterHelper service instance
     * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityFactory $entityFactory,
        CollectionFilterHelper $collectionFilterHelper,
        EntityDisplayHelper $entityDisplayHelper,
        ListEntriesHelper $listHelper,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->collectionFilterHelper = $collectionFilterHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->listHelper = $listHelper;
        $this->featureActivationHelper = $featureActivationHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEntityFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('title', TextType::class, [
            'label' => $this->__('Title') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Enter a title for better management of your locations.')
            ],
            'help' => $this->__('Enter a title for better management of your locations.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the title of the location')
            ],
            'required' => true,
        ]);
        
        $builder->add('street', TextType::class, [
            'label' => $this->__('Street') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the street of the location')
            ],
            'required' => true,
        ]);
        
        $builder->add('numberOfStreet', TextType::class, [
            'label' => $this->__('Number of street') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the number of street of the location')
            ],
            'required' => true,
        ]);
        
        $builder->add('zipCode', TextType::class, [
            'label' => $this->__('Zip code') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the zip code of the location')
            ],
            'required' => true,
        ]);
        
        $builder->add('city', TextType::class, [
            'label' => $this->__('City') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the city of the location')
            ],
            'required' => true,
        ]);
        
        $builder->add('forMap', CheckboxType::class, [
            'label' => $this->__('For map') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('for map ?')
            ],
            'required' => false,
        ]);
        
        $builder->add('radius', IntegerType::class, [
            'label' => $this->__('Radius') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Enter the radius for displaying offers.
                Standard value is 1000.')
            ],
            'help' => [$this->__('Enter the radius for displaying offers.
            Standard value is 1000.'), $this->__f('Note: this value must be less than %maxValue%.', ['%maxValue%' => 5000])],
            'empty_data' => '1000',
            'attr' => [
                'maxlength' => 5,
                'class' => '',
                'title' => $this->__('Enter the radius of the location.') . ' ' . $this->__('Only digits are allowed.')
            ],
            'required' => true,
            'scale' => 0
        ]);
        
        $builder->add('zipCodes', TextType::class, [
            'label' => $this->__('Zip codes') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Here you can enter additional zip codes. If you choose the relevant search option, papershare will also use them, to find offers.
                Enter them comaseperated like (28203,28205,28207) without space.')
            ],
            'help' => $this->__('Here you can enter additional zip codes. If you choose the relevant search option, papershare will also use them, to find offers.
            Enter them comaseperated like (28203,28205,28207) without space.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the zip codes of the location')
            ],
            'required' => false,
        ]);
        
        $listEntries = $this->listHelper->getEntries('location', 'searchOptions');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('searchOptions', ChoiceType::class, [
            'label' => $this->__('Search options') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Possible options:
                - Standard: Papershare is looking for offers with the same city name or the same zip code of your location or zipcode is in additional zip codes.
                - City name: only looking for offers with the same city name like your location.
                - Zip codes: Papershare is looking for offers with the same zip code like your location and additional zip codes')
            ],
            'help' => $this->__('Possible options:
            - Standard: Papershare is looking for offers with the same city name or the same zip code of your location or zipcode is in additional zip codes.
            - City name: only looking for offers with the same city name like your location.
            - Zip codes: Papershare is looking for offers with the same zip code like your location and additional zip codes'),
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the search options')
            ],
            'required' => true,
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        
        $builder->add('private', CheckboxType::class, [
            'label' => $this->__('Private') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('If you set private to active, you say, that it is not a location, you are the owner from; for example a restaurant.')
            ],
            'help' => $this->__('If you set private to active, you say, that it is not a location, you are the owner from; for example a restaurant.'),
            'attr' => [
                'class' => '',
                'title' => $this->__('private ?')
            ],
        	'data' => true,
            'required' => false,
        ]);
        
        $builder->add('name', TextType::class, [
            'label' => $this->__('Name') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Enter the name of your company.')
            ],
            'help' => $this->__('Enter the name of your company.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the name of the location')
            ],
            'required' => false,
        ]);
        
        $builder->add('description', TextareaType::class, [
            'label' => $this->__('Description') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Here you can enter more informations about the company.')
            ],
            'help' => [$this->__('Here you can enter more informations about the company.'), $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000])],
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => '',
                'title' => $this->__('Enter the description of the location')
            ],
            'required' => false,
        ]);
        
        $builder->add('mail', EmailType::class, [
            'label' => $this->__('Mail') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Mail of the company.')
            ],
            'help' => $this->__('Mail of the company.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the mail of the location')
            ],
            'required' => false,
        ]);
        
        $builder->add('website', UrlType::class, [
            'label' => $this->__('Website') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Homepage of your company.')
            ],
            'help' => $this->__('Homepage of your company.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the website of the location')
            ],
            'required' => false,
        ]);
        
        $builder->add('phone', TextType::class, [
            'label' => $this->__('Phone') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the phone of the location')
            ],
            'required' => false,
        ]);
        
        $builder->add('mobile', TextType::class, [
            'label' => $this->__('Mobile') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the mobile of the location')
            ],
            'required' => false,
        ]);
        $this->addGeographicalFields($builder, $options);
    }

    /**
     * Adds fields for coordinates.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addGeographicalFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add('latitude', GeoType::class, [
            'label' => $this->__('Latitude') . ':',
            'required' => false
        ]);
        $builder->add('longitude', GeoType::class, [
            'label' => $this->__('Longitude') . ':',
            'required' => false
        ]);
    }

    /**
     * Adds special fields for moderators.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        if (!$options['has_moderate_permission']) {
            return;
        }
        if ($options['inline_usage']) {
            return;
        }
    
        $builder->add('moderationSpecificCreator', UserLiveSearchType::class, [
            'mapped' => false,
            'label' => $this->__('Creator') . ':',
            'attr' => [
                'maxlength' => 11,
                'title' => $this->__('Here you can choose a user which will be set as creator')
            ],
            'empty_data' => 0,
            'required' => false,
            'help' => $this->__('Here you can choose a user which will be set as creator')
        ]);
        $builder->add('moderationSpecificCreationDate', DateTimeType::class, [
            'mapped' => false,
            'label' => $this->__('Creation date') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('Here you can choose a custom creation date')
            ],
            'empty_data' => '',
            'required' => false,
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'help' => $this->__('Here you can choose a custom creation date')
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], SubmitType::class, [
                'label' => $action['title'],
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass']
                ]
            ]);
            if ($options['mode'] == 'create' && $action['id'] == 'submit' && !$options['inline_usage']) {
                // add additional button to submit item and return to create form
                $builder->add('submitrepeat', SubmitType::class, [
                    'label' => $this->__('Submit and repeat'),
                    'icon' => 'fa-repeat',
                    'attr' => [
                        'class' => $action['buttonClass']
                    ]
                ]);
            }
        }
        $builder->add('reset', ResetType::class, [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'musharemodule_location';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'MU\ShareModule\Entity\LocationEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createLocation();
                },
                'error_mapping' => [
                ],
                'mode' => 'create',
                'actions' => [],
                'has_moderate_permission' => false,
                'filter_by_ownership' => true,
                'inline_usage' => false
            ])
            ->setRequired(['mode', 'actions'])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedTypes('actions', 'array')
            ->setAllowedTypes('has_moderate_permission', 'bool')
            ->setAllowedTypes('filter_by_ownership', 'bool')
            ->setAllowedTypes('inline_usage', 'bool')
            ->setAllowedValues('mode', ['create', 'edit'])
        ;
    }
}
