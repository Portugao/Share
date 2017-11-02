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

namespace MU\ShareModule\Container;

use MU\ShareModule\Container\Base\AbstractLinkContainer;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

use Symfony\Component\Routing\RouterInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use MU\ShareModule\Helper\ControllerHelper;


/**
 * This is the link container service implementation class.
 */
class LinkContainer extends AbstractLinkContainer
{
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;

    /**
     * LinkContainer constructor.
     *
     * @param TranslatorInterface    $translator       Translator service instance
     * @param Routerinterface        $router           Router service instance
     * @param PermissionApiInterface $permissionApi    PermissionApi service instance
     * @param VariableApiInterface   $variableApi      VariableApi service instance
     * @param ControllerHelper       $controllerHelper ControllerHelper service instance
     * @param VariableApiInterface $variableApi     VariableApi service instance
     */
    public function __construct(
    		TranslatorInterface $translator,
    		RouterInterface $router,
    		PermissionApiInterface $permissionApi,
    		VariableApiInterface $variableApi,
    		ControllerHelper $controllerHelper,
    		CurrentUserApiInterface $currentUserApi
    		) {
    			$this->setTranslator($translator);
    			$this->router = $router;
    			$this->permissionApi = $permissionApi;
    			$this->variableApi = $variableApi;
    			$this->controllerHelper = $controllerHelper;
    			$this->currentUserApi = $currentUserApi;
    }
    
    /**
     * Returns available header links.
     *
     * @param string $type The type to collect links for
     *
     * @return array Array of header links
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        $contextArgs = ['api' => 'linkContainer', 'action' => 'getLinks'];
        $allowedObjectTypes = $this->controllerHelper->getObjectTypes('api', $contextArgs);

        $permLevel = LinkContainerInterface::TYPE_ADMIN == $type ? ACCESS_ADMIN : ACCESS_READ;

        // Create an array of links to return
        $links = [];

        if (LinkContainerInterface::TYPE_ACCOUNT == $type) {
            if (!$this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_OVERVIEW)) {
                return $links;
            }

            if (true === $this->variableApi->get('MUShareModule', 'linkOwnLocationsOnAccountPage', true)) {
                $objectType = 'location';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('musharemodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My locations', 'musharemodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }

            if (true === $this->variableApi->get('MUShareModule', 'linkOwnOffersOnAccountPage', true)) {
                $objectType = 'offer';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('musharemodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My offers', 'musharemodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }

            if (true === $this->variableApi->get('MUShareModule', 'linkOwnPoolsOnAccountPage', true)) {
                $objectType = 'pool';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('musharemodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My pools', 'musharemodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }

            if (true === $this->variableApi->get('MUShareModule', 'linkOwnCompaniesOnAccountPage', true)) {
                $objectType = 'company';
                if ($this->permissionApi->hasPermission($this->getBundleName() . ':' . ucfirst($objectType) . ':', '::', ACCESS_READ)) {
                    $links[] = [
                        'url' => $this->router->generate('musharemodule_' . strtolower($objectType) . '_view', ['own' => 1]),
                        'text' => $this->__('My companies', 'musharemodule'),
                        'icon' => 'list-alt'
                    ];
                }
            }

            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('musharemodule_location_adminindex'),
                    'text' => $this->__('Share Backend', 'musharemodule'),
                    'icon' => 'wrench'
                ];
            }


            return $links;
        }

        $routeArea = LinkContainerInterface::TYPE_ADMIN == $type ? 'admin' : '';
        if (LinkContainerInterface::TYPE_ADMIN == $type) {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_READ)) {
                $links[] = [
                    'url' => $this->router->generate('musharemodule_location_index'),
                    'text' => $this->__('Frontend', 'musharemodule'),
                    'title' => $this->__('Switch to user area.', 'musharemodule'),
                    'icon' => 'home'
                ];
            }
        } else {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('musharemodule_location_adminindex'),
                    'text' => $this->__('Backend', 'musharemodule'),
                    'title' => $this->__('Switch to administration area.', 'musharemodule'),
                    'icon' => 'wrench'
                ];
            }
        }
        if ($routeArea != 'admin') {
        	$locationText = $this->__('Map', 'musharemodule');
        	$locationTitle = $this->__('Map for sharing and finding paper', 'musharemodule');
        } else {
        	$locationText = $this->__('Locations', 'musharemodule');
        	$locationTitle = $this->__('List of locations', 'musharemodule');
        }        
        if (in_array('location', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Location:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('musharemodule_location_' . $routeArea . 'view'),
                'text' => $locationText,
                'title' => $locationTitle
            ];
        }
        if ($routeArea != 'admin') {
        	$offerText = $this->__('Your offers', 'musharemodule');
        	$offerTitle = $this->__('The list of your offers', 'musharemodule');
        	$offerUrl = $this->router->generate('musharemodule_offer_' . $routeArea . 'view', array('own' => 1));
        } else {
        	$offerText = $this->__('Offers', 'musharemodule');
        	$offerTitle = $this->__('List of offers', 'musharemodule');
        	$offerUrl = $this->router->generate('musharemodule_offer_' . $routeArea . 'view');
        }
        
        if (in_array('offer', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADD)) {
            $links[] = [
                'url' => $offerUrl,
                'text' => $offerText,
                'title' => $offerTitle
            ];
        }
        $uid = $this->currentUserApi->get('uid');
        if ($routeArea != 'admin' && $uid >= 2) {
        	if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADD)) {
        		$links[] = [
        				'url' => $this->router->generate('zikulaprofilemodule_profile_edit', array('uid' => $uid)),
        				'text' => $this->__('Edit profile', 'musharemodule'),
        				'title' => $this->__('Edit radius or zip codes', 'musharemodule')
        		];
        	}
        }
        
        if ($routeArea == 'admin' && in_array('pool', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Pool:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('musharemodule_pool_' . $routeArea . 'view'),
                'text' => $this->__('Pools', 'musharemodule'),
                'title' => $this->__('Pools list', 'musharemodule')
            ];
        }
        if ($routeArea == 'admin' && in_array('company', $allowedObjectTypes)
            && $this->permissionApi->hasPermission($this->getBundleName() . ':Company:', '::', $permLevel)) {
            $links[] = [
                'url' => $this->router->generate('musharemodule_company_' . $routeArea . 'view'),
                'text' => $this->__('Companies', 'musharemodule'),
                'title' => $this->__('Companies list', 'musharemodule')
            ];
        }
        if ($routeArea == 'admin' && $this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
            $links[] = [
                'url' => $this->router->generate('musharemodule_config_config'),
                'text' => $this->__('Configuration', 'musharemodule'),
                'title' => $this->__('Manage settings for this application', 'musharemodule'),
                'icon' => 'wrench'
            ];
        }

        return $links;
    }
}
