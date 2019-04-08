<?php
namespace Core;

use Web\Request;
use Web\Response;
use Web\Url;
use RevoCMS\RevoCMS;

/**
 * Class Router
 * @package Core
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Router
{
    /**
     * Return an instance of Router
     *
     * @var Router
     */
    private static $instance;

    /**
     * Saved Routes
     *
     * @var array
     */
    protected $routes = array();

    /**
     * Default Route
     *
     * @var string
     */
    private $defaultRoute = null;

    /**
     * @var string
     */
    protected $matchedRoute = null;

    /**
     * @var string
     */
    private $errorCallback = '';

    /**
     * Router constructor.
     */

    public function __construct()
    {
        self::$instance =& $this;
    }

    /**
     * Get Instance of Router
     *
     * @return Router
     */

    public static function &getInstance() : Router
    {
		if(!self::$instance){
			self::$instance = new Router();
		}
		
		return self::$instance;
    }

    /**
     * Call Static Method
     *
     * @param string $method
     * @param array $params
     */
    public static function __callStatic(string $method, array $params)
    {
        $router = self::getInstance();
        $rcms = RevoCMS::getInstance();

        $override = (@!is_null($params[1]["override"])) ? $params[1]["override"] : false;
        $params[0] = ($params[0]!= "") ? $params[0] = "/" . $params[0] : "";
        $params[0] = (@!is_null($params[1]["domain"])) ? $params[1]["domain"] . $params[0] : $rcms->configs->config->get("SITEURL") . $params[0];

        if(strpos($params[0], '.*')){
            $params[0] = str_replace("*", $rcms->configs->config->get("SITEURL"), $params[0]);
        }

        if(@!is_null($params[1]["plugin"])){
            $params[1] = "App\Plugin\\" . $params[1]["plugin"] . "\Controller\\" . $params[1]["controller"] . "@" . $params[1]["method"];
        }else{
            $params[1] = "App\Controller\\" . $params[1]["controller"] . "@" . $params[1]["method"];
        }

        $path = explode("@", $params[1]);
        $path = str_replace("App/", "", str_replace("\\", "/", $path[0]));

        if(file_exists(APPDIR . $path . ".php")){
            $router->addRoute($method, $params[0], $params[1], $override);
        }
    }

    /**
     * Set the Error Callback
     *
     * @param string $callback
     */
    public static function error(string $callback)
    {
        $router = self::getInstance();
        $router->callback($callback);
    }

    /**
     * Add Route
     *
     * @param string $method
     * @param string $route
     * @param string|null $callback
     */
    public static function match(string $method, string $route, string $callback = null)
    {
        $router =& self::getInstance();
		$router->addRoute($method, $route, $callback);
    }

    /**
     * Return Saved Routes
     *
     * @return array
     */

    public function routes() : array
    {
        return $this->routes;
    }

    /**
     * Get Error Calback if defined, null if not
     *
     * @param string|null $callback
     * @return null|string
     */
    public function callback(string $callback = null)
    {
        if (is_null($callback)) {
            return $this->errorCallback;
        } //is_null($callback)
        
        $this->errorCallback = $callback;
        
        return null;
    }

    /**
     * Add Route
     *
     * @param string $method
     * @param string $route
     * @param string|null $callback
     * @param bool $override
     */
    public function addRoute(string $method, string $route, string $callback = null, bool $override = false)
    {
        $methods = array_map('strtoupper', is_array($method) ? $method : array(
            $method
        ));
        $pattern = ltrim($route, '/');
        $route = new Route($methods, $pattern, $callback);
        if($override){
            $custom = array();
            foreach ($this->routes as $r){
                if($r->pattern() != $route->pattern()){
                    array_push($custom, $r);
                }
            }
            $this->routes = $custom;
        }
        array_push($this->routes, $route);
    }

    /**
     * Get Matched Route
     *
     * @return string
     */

    public function matchedRoute() : string
    {
        return $this->matchedRoute;
    }

    /**
     * Invoke a Controller
     *
     * @param string $className
     * @param string $method
     * @param array $params
     * @return bool
     */

    public function invokeController(string $className, string $method, array $params) : bool
    {
        $controller = new $className();
        if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($controller)))) {
            return false;
        } //!in_array(strtolower($method), array_map('strtolower', get_class_methods($controller)))
        call_user_func_array(array(
            $controller,
            $method
        ), $params);
        return true;
    }

    /**
     * Invoke a Method
     *
     * @param string $callback
     * @param array $params
     * @return bool
     */

    public function invokeObject(string $callback, array $params = array()) : bool
    {
        if (is_object($callback)) {
            call_user_func_array($callback, $params);
            return true;
        } //is_object($callback)
        $segments = explode('@', $callback);
        
        $controller = $segments[0];
        $method     = $segments[1];
        if ((($method[0] !== '_')) && class_exists($controller) && method_exists($controller, $method)) {
            return $this->invokeController($controller, $method, $params);
        } else{
            $this->invokeObject($this->callback(), ["error" => "La méthode '" . $method . "' n'existe pas dans le controller '" . substr($controller, strrpos($controller, '\\') + 1) . "'!"]);
        }
        return false;
    }

    /**
     * Dispatch Method
     *
     * @return bool
     */

    public function dispatch() : bool
    {
        $uri = Url::detectUri(true);
        if(substr($uri, -1) == "/"){
            $uri = substr($uri, 0, -1);
        }
        if (Request::isGet() && $this->dispatchFile($uri)) {
            return true;
        } //Request::isGet() && $this->dispatchFile($uri)
        $method = Request::getMethod();

        if ($this->defaultRoute !== null) {
            array_push($this->routes, $this->defaultRoute);
        } //$this->defaultRoute !== null

        foreach ($this->routes as $route) {
            if ($route->match($uri, $method)) {
                $this->matchedRoute = $route;

                $callback = $route->callback();

                if ($callback !== null) {
                    return $this->invokeObject($callback, $route->params());
                } //$callback !== null

                return true;
            } //$route->match($uri, $method)
        } //$this->routes as $route

        $this->invokeObject($this->callback());

        return false;
    }


    /**
     * Dispatch File Method
     *
     * @param string $uri
     * @return bool
     */

    protected function dispatchFile(string $uri) : bool
    {
        $filePath = '';
        $rcms = RevoCMS::getInstance();
        
        if (preg_match('#^assets/(.*)$#i', $uri, $matches)) {
            $filePath = ROOTDIR . 'assets' . DS . $matches[1];
        } //preg_match('#^assets/(.*)$#i', $uri, $matches)
        
		if(count($rcms->customRessources) != 0){
			foreach($rcms->customRessources as $file){
				if($file["slug"] == $uri){
					$filePath = $file["path"];
				}
			}
		}
		
        if (!empty($filePath)) {
            Response::serveFile($filePath);
            
            return true;
        } //!empty($filePath)
        
        return false;
    }
    
}
