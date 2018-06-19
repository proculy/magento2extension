<?php
/**
 * @copyright Copyright © 2018 Vvc. All rights reserved.
 * @author    quang.lht@vn.vinx.asia
 */

namespace Vvc\Task\Controller;

Use Magento\Framework\Url;

class CustomRouter implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;
 
    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;
 
    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response
    )
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }
 
    /**
     * Validate and Match
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        /*
         * We will search “examplerouter” and “exampletocms” words and make forward depend on word
         * -examplerouter will forward to base router to match task front name, test controller path and test controller class
         * -exampletocms will set front name to cms, controller path to page and action to view
         */
        $identifier = trim($request->getPathInfo(), '/');
        
        //patern to capture the controllerName 
        //controller Or controller/xxx        
        $patern = '~(?P<controller>[^/]+)(/.*)?$~';
        $controller = [];
        preg_match($patern, $identifier, $controller);
        
        if (!array_key_exists('controller', $controller)) {
            return;
        }

        if (trim($controller['controller']) === 'exampletocms') {
            /*
             * We must set module, controller path and action name + we will set page id 5 witch is about us page on
             * default magento 2 installation with sample data.
             */
            $request->setModuleName('cms')->setControllerName('page')->setActionName('view')->setParam('page_id', 5);        
        } else if (trim($controller['controller']) === 'examplerouter') {
            /*
             * We must set module, controller path and action name for our controller class(task/index/test.php)
             */
            $params = [];
            
            //patern to capture the controllerName and params
            //controller/id/name/content   
            $patern = '~(?P<controller>[^/]+)/?(?P<id>[0-9]+[^/]?)?/?(?P<name>[a-zA-Z0-9]+[^/]?)?/?(?P<content>[a-zA-Z0-9]+?)?$~';
            $keys = Array('id', 'name', 'content');
            $matches = [];
            preg_match($patern, $identifier, $matches);
            
            foreach ($keys as $key) {
                if (array_key_exists($key, $matches)) {
                    $params[$key] = $matches[$key];
                }
            }
            
            $request->setModuleName('task')->setControllerName('index')->setActionName('test')->setParams($params);
        } else if (trim($controller['controller']) === 'tasklist') {
            $request->setModuleName('task')->setControllerName('index')->setActionName('tasklist');
        } else if (trim($controller['controller']) === 'updatetask') {                           
            //patern to capture the controllerName and params
            //controller/id/name
            $patern = '~(?P<controller>[^/]+)/?(?P<id>[0-9]+[^/]?)?/?(?P<name>[a-zA-Z0-9]+?)?$~';
            $keys = Array('id', 'name');
            $params = [];
            $matches = [];            
            preg_match($patern, $identifier, $matches);
            
            foreach ($keys as $key) {
                if (array_key_exists($key, $matches)) {
                    $params[$key] = $matches[$key];
                }
            }
            
            $request->setModuleName('task')->setControllerName('index')->setActionName('updatetask')->setParams($params);
        } else {
            //There is no match
            return;
        }
 
        /*
         * We have match and now we will forward action
         */
        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}