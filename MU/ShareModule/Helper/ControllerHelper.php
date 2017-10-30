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

use MU\ShareModule\Helper\Base\AbstractControllerHelper;

use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\RouteUrl;
use ServiceUtil;

/**
 * Helper implementation class for controller layer methods.
 */
class ControllerHelper extends AbstractControllerHelper
{
    /**
     * Processes the parameters for a view action.
     * This includes handling pagination, quick navigation forms and other aspects.
     *
     * @param string          $objectType         Name of treated entity type
     * @param SortableColumns $sortableColumns    Used SortableColumns instance
     * @param array           $templateParameters Template data
     * @param boolean         $hasHookSubscriber  Whether hook subscribers are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processViewActionParameters($objectType, SortableColumns $sortableColumns, array $templateParameters = [], $hasHookSubscriber = false)
    {
        $contextArgs = ['controller' => $objectType, 'action' => 'view'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
            throw new \Exception($this->__('Error! Invalid object type received.'));
        }
    
        $request = $this->request;
        $repository = $this->entityFactory->getRepository($objectType);
    
        // parameter for used sorting field
        $sort = $request->query->get('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
            $request->query->set('sort', $sort);
            // set default sorting in route parameters (e.g. for the pager)
            $routeParams = $request->attributes->get('_route_params');
            $routeParams['sort'] = $sort;
            $request->attributes->set('_route_params', $routeParams);
        }
        $sortdir = $request->query->get('sortdir', 'ASC');
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = strtolower($sortdir);
    
        $templateParameters['all'] = 'csv' == $request->getRequestFormat() ? 1 : $request->query->getInt('all', 0);
        $templateParameters['own'] = $request->query->getInt('own', $this->variableApi->get('MUShareModule', 'showOnlyOwnEntries', 0));
    
        $resultsPerPage = 0;
        if ($templateParameters['all'] != 1) {
            // the number of items displayed on a page for pagination
            $resultsPerPage = $request->query->getInt('num', 0);
            if (in_array($resultsPerPage, [0, 10])) {
                $resultsPerPage = $this->variableApi->get('MUShareModule', $objectType . 'EntriesPerPage', 10);
            }
        }
        $templateParameters['num'] = $resultsPerPage;
        $templateParameters['tpl'] = $request->query->getAlnum('tpl', '');
    
        $templateParameters = $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
    
        $quickNavForm = $this->formFactory->create('MU\ShareModule\Form\Type\QuickNavigation\\' . ucfirst($objectType) . 'QuickNavType', $templateParameters);
        if ($quickNavForm->handleRequest($request) && $quickNavForm->isSubmitted()) {
            $quickNavData = $quickNavForm->getData();
            foreach ($quickNavData as $fieldName => $fieldValue) {
                if ($fieldName == 'routeArea') {
                    continue;
                }
                if (in_array($fieldName, ['all', 'own', 'num'])) {
                    $templateParameters[$fieldName] = $fieldValue;
                } elseif ($fieldName == 'sort' && !empty($fieldValue)) {
                    $sort = $fieldValue;
                } elseif ($fieldName == 'sortdir' && !empty($fieldValue)) {
                    $sortdir = $fieldValue;
                } elseif (false === stripos($fieldName, 'thumbRuntimeOptions')) {
                    // set filter as query argument, fetched inside repository
                    $request->query->set($fieldName, $fieldValue);
                }
            }
        }
        $sortableColumns->setOrderBy($sortableColumns->getColumn($sort), strtoupper($sortdir));
        $resultsPerPage = $templateParameters['num'];
    
        $urlParameters = $templateParameters;
        foreach ($urlParameters as $parameterName => $parameterValue) {
            if (false === stripos($parameterName, 'thumbRuntimeOptions')) {
                continue;
            }
            unset($urlParameters[$parameterName]);
        }
    
        $sort = $sortableColumns->getSortColumn()->getName();
        $sortdir = $sortableColumns->getSortDirection();
        $sortableColumns->setAdditionalUrlParameters($urlParameters);
    
        $uid = $this->currentUserApi->get('uid');
        $where = 'tbl.createdBy = ' . $uid;
        if ($templateParameters['all'] == 1) {
            // retrieve item list without pagination
            $entities = $repository->selectWhere($where, $sort . ' ' . $sortdir);
        } else {
            // the current offset which is used to calculate the pagination
            $currentPage = $request->query->getInt('pos', 1);
    
            // retrieve item list with pagination
            list($entities, $objectCount) = $repository->selectWherePaginated($where, $sort . ' ' . $sortdir, $currentPage, $resultsPerPage);
    
            $templateParameters['currentPage'] = $currentPage;
            $templateParameters['pager'] = [
                'amountOfItems' => $objectCount,
                'itemsPerPage' => $resultsPerPage
            ];
        }
    
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = $sortdir;
        $templateParameters['items'] = $entities;
    
    
        if (true === $hasHookSubscriber) {
            // build RouteUrl instance for display hooks
            $urlParameters['_locale'] = $request->getLocale();
            $templateParameters['currentUrlObject'] = new RouteUrl('musharemodule_' . strtolower($objectType) . '_view', $urlParameters);
        }
    
        $templateParameters['sort'] = $sortableColumns->generateSortableColumns();
        $templateParameters['quickNavForm'] = $quickNavForm->createView();
        
        // own code
        if ($objectType == 'location' && $templateParameters['routeArea'] == '') {
        	
        	$offerRepository = $this->entityFactory->getRepository('offer');
        	$where = 'tbl.createdBy = ' . $uid;
        	$where .= ' AND ';
        	$where .= 'tbl.forMap = 1';
        	
        	// we get the location set for the map of the actual user
        	$myLocation = $repository->selectWhere($where);
        	if (count($myLocation) > 1) {
        		$this->logger->info(__('Uups. You have more than one private location activated for the map. Please edit your locations so only one is activated for the map view!'));
        		//LogUtil::registerError(__('Uups. You have more than one private location activated for the map. Please edit your locations so only one is activated for the map view!', $dom));
        		$redirecturl = ModUtil::url($this->name, 'user', 'view', array('ot' => 'location', 'own' => 1));
        		return System::redirect($redirecturl);
        	}
        	if (!isset($myLocation)) {
        		$this->logger->info(__('Uups. You have more than one private location activated for the map. Please edit your locations so only one is activated for the map view!'));
        		//LogUtil::registerError(__('Uups. You have more than one private location activated for the map. Please edit your locations so only one is activated for the map view!', $dom));
        		$redirecturl = ModUtil::url($this->name, 'user', 'view', array('ot' => 'location', 'own' => 1));
        		return System::redirect($redirecturl);
        	}
        	if ($myLocation) {
        	    $templateParameters['myLocation'] = $myLocation[0];
        	} else {
        		$templateParameters['defaultLatitude'] = $this->variableApi->get('MUShareModule', 'defaultLatitude');
        		$templateParameters['defaultLongitude'] = $this->variableApi->get('MUShareModule', 'defaultLongitude');
        	}
        	
        	// radius is the radius set by the actual user, standard setting
        	$radius = 1000;

        	// the single offers, that have no other offers entity with
        	// the same lat and longitude
        	$where2 = 'tbl.createdBy != ' . $uid;
        	$where2 .= ' AND ';
        	$where2 .= 'tbl.isOpen = 1';
        	$where2 .= ' AND ';
        	$where2 .= 'tbl.pool is NULL'; 
        	/*$where2 .= ' AND ';
        	$where2 .= 'tbl.city = ' . $myLocation[0]['city'];*/

        	// we get all single offers
        	$singleOffers = $offerRepository->selectWhere($where2);

        	if ($singleOffers) {
        	// we check the distance for each found location
        	foreach ($singleOffers as $singleOffer) {
        		$distance = acos(sin(deg2rad($singleOffer['latitude']))*sin(deg2rad($myLocation[0]['latitude']))+cos(deg2rad($singleOffer['latitude']))*cos(deg2rad($myLocation[0]['latitude']))*cos(deg2rad($singleOffer['longitude']) - deg2rad($myLocation[0]['longitude'])))*6375;

        		if ($distance <= $radius/1000) {
            		$relevantSingleOffers[] = $singleOffer;
        		}
        	}
        	if ($relevantSingleOffers)
        	$templateParameters['singleOffers'] = $relevantSingleOffers; 
        	}
        	
        	// we get Offers in pools
        	$where3 = 'tbl.createdBy != ' . $uid;
        	$where3 .= ' AND ';
        	$where3 .= 'tbl.isOpen = 1';
        	$where3 .= ' AND ';
        	$where3 .= 'tbl.pool is not NULL';
        	
        	// we get all pool offers
        	$poolOffers = $offerRepository->selectWhere($where3);

        	if ($poolOffers) {
        		// we check the distance for each found Offer
        		foreach ($poolOffers as $poolOffer) {
        			$distance = acos(sin(deg2rad($poolOffer['latitude']))*sin(deg2rad($myLocation[0]['latitude']))+cos(deg2rad($poolOffer['latitude']))*cos(deg2rad($myLocation[0]['latitude']))*cos(deg2rad($poolOffer['longitude']) - deg2rad($myLocation[0]['longitude'])))*6375;
        	
        			if ($distance <= $radius/1000) {
                    	$relevantPoolOffers[] = $poolOffer;
        			}
        		}
        		$pools = array();
        		foreach ($relevantPoolOffers as $relevantPoolOffer) {
        			if (!in_array($relevantPoolOffer['pool'], $pools)) {
        				$pools[] = $relevantPoolOffer['pool'];
        			}     			
        		}

        	}
        	
        	// we work with pools 
        	if (isset($pools)) {
        	    $templateParameters['pools'] = $pools;
        	}
        	
        	/*$offerRepository = $this->entityFactory->getRepository('offer');
        	$where4 = 'tbl.isOpen = 1';
        	$offers = $offerRepository->selectWhere($where4);

        	$templateParameters['offers'] = $offers;*/
        	$templateParameters['radius'] = $radius;
        }
    
        $templateParameters['canBeCreated'] = $this->modelHelper->canBeCreated($objectType);
    
        return $templateParameters;
    }
    
    /**
     * Processes the parameters for a display action.
     *
     * @param string  $objectType         Name of treated entity type
     * @param array   $templateParameters Template data
     * @param boolean $hasHookSubscriber  Whether hook subscribers are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processDisplayActionParameters($objectType, array $templateParameters = [], $hasHookSubscriber = false)
    {
    	$contextArgs = ['controller' => $objectType, 'action' => 'display'];
    	if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
    		throw new \Exception($this->__('Error! Invalid object type received.'));
    	}
    
    	if (true === $hasHookSubscriber) {
    		// build RouteUrl instance for display hooks
    		$entity = $templateParameters[$objectType];
    		$urlParameters = $entity->createUrlArgs();
    		$urlParameters['_locale'] = $this->request->getLocale();
    		$templateParameters['currentUrlObject'] = new RouteUrl('musharemodule_' . strtolower($objectType) . '_display', $urlParameters);
    	}
    	
    	if ($objectType == 'location' && $templateParameters['routeArea'] != 'admin') {
    		$uid = $this->currentUserApi->get('uid');
    		if ($entity['createdBy'] != $uid) {
    			$url = new RouteUrl('musharemodule_location_view');
    			$serviceContainer = \ServiceUtil::getService();
    			$serviceContainer->get('mu'); //TODO
    			
    		}
    	}
    
    	return $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
    }
}
