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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use MU\ShareModule\Entity\LocationEntity;
use MU\ShareModule\Entity\OfferEntity;
use MU\ShareModule\Entity\PoolEntity;
use MU\ShareModule\Entity\CompanyEntity;
use MU\ShareModule\Helper\CategoryHelper;

/**
 * Entity collection filter helper base class.
 */
abstract class AbstractCollectionFilterHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;

    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var bool Fallback value to determine whether only own entries should be selected or not
     */
    protected $showOnlyOwnEntries = false;

    /**
     * CollectionFilterHelper constructor.
     *
     * @param RequestStack   $requestStack        RequestStack service instance
     * @param CurrentUserApiInterface $currentUserApi CurrentUserApi service instance
     * @param CategoryHelper $categoryHelper      CategoryHelper service instance
     * @param bool           $showOnlyOwnEntries  Fallback value to determine whether only own entries should be selected or not
     */
    public function __construct(
        RequestStack $requestStack,
        CurrentUserApiInterface $currentUserApi,
        CategoryHelper $categoryHelper,
        $showOnlyOwnEntries
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->currentUserApi = $currentUserApi;
        $this->categoryHelper = $categoryHelper;
        $this->showOnlyOwnEntries = $showOnlyOwnEntries;
    }

    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $objectType Name of treated entity type
     * @param string $context    Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args       Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    public function getViewQuickNavParameters($objectType = '', $context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        if ($objectType == 'location') {
            return $this->getViewQuickNavParametersForLocation($context, $args);
        }
        if ($objectType == 'offer') {
            return $this->getViewQuickNavParametersForOffer($context, $args);
        }
        if ($objectType == 'pool') {
            return $this->getViewQuickNavParametersForPool($context, $args);
        }
        if ($objectType == 'company') {
            return $this->getViewQuickNavParametersForCompany($context, $args);
        }
    
        return [];
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCommonViewFilters($objectType, QueryBuilder $qb)
    {
        if ($objectType == 'location') {
            return $this->addCommonViewFiltersForLocation($qb);
        }
        if ($objectType == 'offer') {
            return $this->addCommonViewFiltersForOffer($qb);
        }
        if ($objectType == 'pool') {
            return $this->addCommonViewFiltersForPool($qb);
        }
        if ($objectType == 'company') {
            return $this->addCommonViewFiltersForCompany($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function applyDefaultFilters($objectType, QueryBuilder $qb, $parameters = [])
    {
        if ($objectType == 'location') {
            return $this->applyDefaultFiltersForLocation($qb, $parameters);
        }
        if ($objectType == 'offer') {
            return $this->applyDefaultFiltersForOffer($qb, $parameters);
        }
        if ($objectType == 'pool') {
            return $this->applyDefaultFiltersForPool($qb, $parameters);
        }
        if ($objectType == 'company') {
            return $this->applyDefaultFiltersForCompany($qb, $parameters);
        }
    
        return $qb;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForLocation($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['companyOfLocation'] = $this->request->query->get('companyOfLocation', 0);
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['q'] = $this->request->query->get('q', '');
        $parameters['private'] = $this->request->query->get('private', '');
        $parameters['forMap'] = $this->request->query->get('forMap', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForOffer($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['catId'] = $this->request->query->get('catId', '');
        $parameters['catIdList'] = $this->categoryHelper->retrieveCategoriesFromRequest('offer', 'GET');
        $parameters['locationOfOffer'] = $this->request->query->get('locationOfOffer', 0);
        $parameters['pool'] = $this->request->query->get('pool', 0);
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['period'] = $this->request->query->get('period', '');
        $parameters['q'] = $this->request->query->get('q', '');
        $parameters['isOpen'] = $this->request->query->get('isOpen', '');
        $parameters['abo'] = $this->request->query->get('abo', '');
        $parameters['totalYear'] = $this->request->query->get('totalYear', '');
        $parameters['splitCosts'] = $this->request->query->get('splitCosts', '');
        $parameters['back'] = $this->request->query->get('back', '');
        $parameters['atMe'] = $this->request->query->get('atMe', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForPool($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['q'] = $this->request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args    Additional arguments
     *
     * @return array List of template variables to be assigned
     */
    protected function getViewQuickNavParametersForCompany($context = '', $args = [])
    {
        $parameters = [];
        if (null === $this->request) {
            return $parameters;
        }
    
        $parameters['workflowState'] = $this->request->query->get('workflowState', '');
        $parameters['q'] = $this->request->query->get('q', '');
        $parameters['isPublic'] = $this->request->query->get('isPublic', '');
    
        return $parameters;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForLocation(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForLocation();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('location', $qb, $v);
                }
            } elseif (in_array($k, ['private', 'forMap'])) {
                // boolean filter
                if ($v == 'no') {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ($v == 'yes' || $v == '1') {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForLocation($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForOffer(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForOffer();
        foreach ($parameters as $k => $v) {
            if ($k == 'catId') {
                // single category filter
                if ($v > 0) {
                    $qb->andWhere('tblCategories.category = :category')
                       ->setParameter('category', $v);
                }
            } elseif ($k == 'catIdList') {
                // multi category filter
                /* old
                $qb->andWhere('tblCategories.category IN (:categories)')
                   ->setParameter('categories', $v);
                 */
                $qb = $this->categoryHelper->buildFilterClauses($qb, 'offer', $v);
            } elseif (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('offer', $qb, $v);
                }
            } elseif (in_array($k, ['isOpen', 'abo', 'totalYear', 'splitCosts', 'back', 'atMe'])) {
                // boolean filter
                if ($v == 'no') {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ($v == 'yes' || $v == '1') {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForOffer($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForPool(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForPool();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('pool', $qb, $v);
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForPool($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param QueryBuilder $qb Query builder to be enhanced
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function addCommonViewFiltersForCompany(QueryBuilder $qb)
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        if (false !== strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForCompany();
        foreach ($parameters as $k => $v) {
            if (in_array($k, ['q', 'searchterm'])) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('company', $qb, $v);
                }
            } elseif (in_array($k, ['isPublic'])) {
                // boolean filter
                if ($v == 'no') {
                    $qb->andWhere('tbl.' . $k . ' = 0');
                } elseif ($v == 'yes' || $v == '1') {
                    $qb->andWhere('tbl.' . $k . ' = 1');
                }
            } else if (!is_array($v)) {
                // field filter
                if ((!is_numeric($v) && $v != '') || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, substr($v, 1, strlen($v)-1));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . $v . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFiltersForCompany($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForLocation(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'musharemodule_location_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if (!in_array('workflowState', array_keys($parameters)) || empty($parameters['workflowState'])) {
            // per default we show approved locations only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForOffer(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'musharemodule_offer_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForPool(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'musharemodule_pool_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param array        $parameters List of determined filter options
     *
     * @return QueryBuilder Enriched query builder instance
     */
    protected function applyDefaultFiltersForCompany(QueryBuilder $qb, $parameters = [])
    {
        if (null === $this->request) {
            return $qb;
        }
        $routeName = $this->request->get('_route');
        $isAdminArea = false !== strpos($routeName, 'musharemodule_company_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool)$this->request->query->getInt('own', $this->showOnlyOwnEntries);
    
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds a where clause for search query.
     *
     * @param string       $objectType Name of treated entity type
     * @param QueryBuilder $qb         Query builder to be enhanced
     * @param string       $fragment   The fragment to search for
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addSearchFilter($objectType, QueryBuilder $qb, $fragment = '')
    {
        if ($fragment == '') {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
        if ($objectType == 'location') {
            $filters[] = 'tbl.workflowState = :searchWorkflowState';
            $parameters['searchWorkflowState'] = $fragment;
            $filters[] = 'tbl.title LIKE :searchTitle';
            $parameters['searchTitle'] = '%' . $fragment . '%';
            $filters[] = 'tbl.street LIKE :searchStreet';
            $parameters['searchStreet'] = '%' . $fragment . '%';
            $filters[] = 'tbl.numberOfStreet LIKE :searchNumberOfStreet';
            $parameters['searchNumberOfStreet'] = '%' . $fragment . '%';
            $filters[] = 'tbl.zipCode LIKE :searchZipCode';
            $parameters['searchZipCode'] = '%' . $fragment . '%';
            $filters[] = 'tbl.city LIKE :searchCity';
            $parameters['searchCity'] = '%' . $fragment . '%';
        }
        if ($objectType == 'offer') {
            $filters[] = 'tbl.product LIKE :searchProduct';
            $parameters['searchProduct'] = '%' . $fragment . '%';
            $filters[] = 'tbl.description LIKE :searchDescription';
            $parameters['searchDescription'] = '%' . $fragment . '%';
            $filters[] = 'tbl.period = :searchPeriod';
            $parameters['searchPeriod'] = $fragment;
            $filters[] = 'tbl.zipCode LIKE :searchZipCode';
            $parameters['searchZipCode'] = '%' . $fragment . '%';
            $filters[] = 'tbl.meetingPlace LIKE :searchMeetingPlace';
            $parameters['searchMeetingPlace'] = '%' . $fragment . '%';
        }
        if ($objectType == 'pool') {
            $filters[] = 'tbl.collectionOfPool LIKE :searchCollectionOfPool';
            $parameters['searchCollectionOfPool'] = '%' . $fragment . '%';
        }
        if ($objectType == 'company') {
            $filters[] = 'tbl.name LIKE :searchName';
            $parameters['searchName'] = '%' . $fragment . '%';
            $filters[] = 'tbl.description LIKE :searchDescription';
            $parameters['searchDescription'] = '%' . $fragment . '%';
            $filters[] = 'tbl.mail = :searchMail';
            $parameters['searchMail'] = $fragment;
            $filters[] = 'tbl.website = :searchWebsite';
            $parameters['searchWebsite'] = $fragment;
            $filters[] = 'tbl.phone LIKE :searchPhone';
            $parameters['searchPhone'] = '%' . $fragment . '%';
            $filters[] = 'tbl.mobile LIKE :searchMobile';
            $parameters['searchMobile'] = '%' . $fragment . '%';
        }
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')');
    
        foreach ($parameters as $parameterName => $parameterValue) {
            $qb->setParameter($parameterName, $parameterValue);
        }
    
        return $qb;
    }
    
    /**
     * Adds a filter for the createdBy field.
     *
     * @param QueryBuilder $qb     Query builder to be enhanced
     * @param integer      $userId The user identifier used for filtering
     *
     * @return QueryBuilder Enriched query builder instance
     */
    public function addCreatorFilter(QueryBuilder $qb, $userId = null)
    {
        if (null === $userId) {
            $userId = $this->currentUserApi->isLoggedIn() ? $this->currentUserApi->get('uid') : UsersConstant::USER_ID_ANONYMOUS;
        }
    
        if (is_array($userId)) {
            $qb->andWhere('tbl.createdBy IN (:userIds)')
               ->setParameter('userIds', $userId);
        } else {
            $qb->andWhere('tbl.createdBy = :userId')
               ->setParameter('userId', $userId);
        }
    
        return $qb;
    }
}
