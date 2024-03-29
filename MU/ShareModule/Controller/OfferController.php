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

namespace MU\ShareModule\Controller;

use MU\ShareModule\Controller\Base\AbstractOfferController;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use MU\ShareModule\Entity\OfferEntity;

/**
 * Offer controller class providing navigation and interaction functionality.
 */
class OfferController extends AbstractOfferController
{
    /**
     * @inheritDoc
     *
     * @Route("/admin/offers",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return parent::adminIndexAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/offers",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/offers/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|xml|json|kml"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::adminViewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/offers/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|xml|json|kml"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::viewAction($request, $sort, $sortdir, $pos, $num);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/offer/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html|xml|json|kml"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("offer", class="MUShareModule:OfferEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="offer.getUpdatedDate()", ETag="'Offer' ~ offer.getid() ~ offer.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param OfferEntity $offer Treated offer instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if offer to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, OfferEntity $offer)
    {
        return parent::adminDisplayAction($request, $offer);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/offer/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html|xml|json|kml"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("offer", class="MUShareModule:OfferEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="offer.getUpdatedDate()", ETag="'Offer' ~ offer.getid() ~ offer.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param OfferEntity $offer Treated offer instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if offer to be displayed isn't found
     */
    public function displayAction(Request $request, OfferEntity $offer)
    {
        return parent::displayAction($request, $offer);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/offer/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if offer to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return parent::adminEditAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/offer/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if offer to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/offer/delete/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("offer", class="MUShareModule:OfferEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="offer.getUpdatedDate()", ETag="'Offer' ~ offer.getid() ~ offer.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param OfferEntity $offer Treated offer instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if offer to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, OfferEntity $offer)
    {
        return parent::adminDeleteAction($request, $offer);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/offer/delete/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("offer", class="MUShareModule:OfferEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="offer.getUpdatedDate()", ETag="'Offer' ~ offer.getid() ~ offer.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param OfferEntity $offer Treated offer instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if offer to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, OfferEntity $offer)
    {
        return parent::deleteAction($request, $offer);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/admin/offers/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return parent::adminHandleSelectedEntriesAction($request);
    }
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/offers/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return parent::handleSelectedEntriesAction($request);
    }
    
    /**
     * This method includes the common implementation code for adminDisplay() and display().
     */
    protected function displayInternal(Request $request, OfferEntity $offer, $isAdmin = false)
    {
    	$session = $request->getSession();
    	$session->set('offerDisplay', $offer['id']);
    	
    	return parent::displayInternal($request, $offer);
    }
    
    /**
     * This method includes the common implementation code for adminEdit() and edit().
     */
    protected function editInternal(Request $request, $isAdmin = false)
    {
    	// parameter specifying which type of objects we are treating
    	$objectType = 'offer';
    	$permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_EDIT;
    	if (!$this->hasPermission('MUShareModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
    		throw new AccessDeniedException();
    	}
    	$templateParameters = [
    			'routeArea' => $isAdmin ? 'admin' : ''
    	];
    
    	$controllerHelper = $this->get('mu_share_module.controller_helper');
    	$templateParameters = $controllerHelper->processEditActionParameters($objectType, $templateParameters);
    
    	// delegate form processing to the form handler
    	$formHandler = $this->get('mu_share_module.form.handler.offer');
    	$result = $formHandler->processForm($templateParameters);
    	if ($result instanceof RedirectResponse) {
    		return $result;
    	}
    
    	$templateParameters = $formHandler->getTemplateParameters();
    	if ($templateParameters['routeArea'] != 'admin') {
    		$templateParameters['locationId'] = $request->get('locationofoffer');  	
    	} else {
    		
    	}
    
    	// fetch and return the appropriate template
    	return $this->get('mu_share_module.view_helper')->processTemplate($objectType, 'edit', $templateParameters);
    }

    // feel free to add your own controller methods here
}
