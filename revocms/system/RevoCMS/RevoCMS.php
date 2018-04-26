<?php
namespace RevoCMS;

use Config\Config;
use Dir\DirManager;
use Event\EventPublisher;
use Events\Theme\ThemeLoadEvent;
use Events\Plugin\PluginLoadEvent;
use Core\Translate;
use App\Model\Permissions;
use App\Model\Ranks;
use Cache\Cache;

/**
 * Class RevoCMS
 * @package RevoCMS
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class RevoCMS {

    /**
     * Instance of RevoCMS
     * @var RevoCMS
     */
	private static $instance;

    /**
     * RevoCMS constructor.
     */

    private function __construct()
    {
        self::$instance = $this;

		$this->events = EventPublisher::getInstance();
		$this->plugins = new \stdClass();
        $this->plugins->outdated = new \stdClass();
        $this->plugins->unavailable = new \stdClass();
        $this->plugins->available = new \stdClass();
        $this->plugins->activated = new \stdClass();
        $this->plugins->configs = new \stdClass();
        $this->themes = new \stdClass();
        $this->themes->outdated = new \stdClass();
        $this->themes->unavailable = new \stdClass();
        $this->themes->available = new \stdClass();
        $this->configs = new \stdClass();
		$this->customRessources = array();

        $this->loadConfigs();
        $this->loadListeners();
        $this->loadRoutes();
        $this->loadThemes();

        $this->permissions = new Permissions();
        $this->ranks = new Ranks();

        $this->permissions->list = array();

        $this->loadPlugins();
    }

    /**
     * Get the instance of RevoCMS
     * @return RevoCMS
     */

	public static function getInstance() : RevoCMS {
		if(!self::$instance){
			self::$instance = new RevoCMS();
		}
		
		return self::$instance;
	}

    /**
     * Load Configs Method
     */

	private function loadConfigs(){

        $cache = new Cache(["name" => "configs", "path" => TMPDIR . "cache/configs/", "extension" => ".cache"]);
        if (!$cache->isCached("configs")) {
            $dirManager = new DirManager();
            $cache->store("configs", $dirManager->getIni(ROOTDIR . "config"), 60*10);
        }
        $ini = $cache->retrieve("configs");

		foreach($ini as $file){
			$tmp = basename($file);
			$tmp = str_replace(".ini", "", $tmp);
			$this->configs->$tmp = new Config($file);
		}
	}

    /**
     * Load Listeners Method
     *
     * @param string|null $path
     */

	private function loadListeners(string $path = null){
		if($path == null) $path = APPDIR . "Listeners";

        $cache = new Cache(["name" => "listeners", "path" => TMPDIR . "cache/listeners/", "extension" => ".cache"]);
        if (!$cache->isCached("listeners")) {
            $dirManager = new DirManager();
            $cache->store("listeners", $dirManager->getListenersFiles($path), 60);
        }
		$listener = $cache->retrieve("listeners");
		foreach($listener as $file){
			require_once $file;
		}
	}

    /**
     * Load Themes Method
     */

	private function loadThemes(){

        $cache = new Cache(["name" => "templates", "path" => TMPDIR . "cache/templates/", "extension" => ".cache"]);
        if (!$cache->isCached("templates")) {
            $dirManager = new DirManager();
            $cache->store("templates", $dirManager->getJson(APPDIR . "Template"), 60*5);
        }
        $theme = $cache->retrieve("templates");

        $lang = $this->configs->config->get("LANGUAGE_CODE");
	    foreach ($theme as $file){
            $cache_name = "template_" . $file;
            $cache = new Cache(["name" => $cache_name, "path" => TMPDIR . "cache/templates/", "extension" => ".cache"]);
            if (!$cache->isCached($cache_name)) {
                $cache->store($cache_name, json_decode(file_get_contents($file), false), 60*5);
            }
            $data = $cache->retrieve($cache_name);
            $slug = $data->slug;
            if(@!is_null($data->translate->supported->$lang)){
                $this->themes->available->$slug = $data;
                $this->events->raise(new ThemeLoadEvent($file, $data));
                if($data->slug == $this->configs->config->get("TEMPLATE")){
                    $this->themes->selected = $data;
                }
            }else{
                $this->themes->unavailable->$slug = $data;
            }
        }
    }

    /**
     * Load Routes Method
     */

    private function loadRoutes(){

        $cache = new Cache(["name" => "routes", "path" => TMPDIR . "cache/routes/", "extension" => ".cache"]);
        if (!$cache->isCached("routes")) {
            $dirManager = new DirManager();
            $cache->store("routes", $dirManager->getRoutesFiles(APPDIR . "Route"), 60);
        }
        $routesFiles = $cache->retrieve("routes");

        foreach($routesFiles as $file){
            require_once $file;
        }
    }

    /**
     * Load Route(s) in a specified directory
     *
     * @param string $path
     */

    private function loadRoutesInFolder(string $path){

        $cache_name = "caches_" . $path;
        $cache = new Cache(["name" => $cache_name, "path" => TMPDIR . "cache/routes/", "extension" => ".cache"]);
        if (!$cache->isCached($cache_name)) {
            $dirManager = new DirManager();
            $cache->store($cache_name, $dirManager->getRoutesFiles($path), 60*2);
        }
        $routesFiles = $cache->retrieve($cache_name);

        foreach($routesFiles as $file){
            require_once $file;
        }
    }

    /**
     * Load Config(s) in a specified plugin directory
     *
     * @param string $path
     * @param string $slug
     */

    private function loadConfigInFolder(string $path, string $slug){

        $cache_name = "configs_" . $path;
        $cache = new Cache(["name" => $cache_name, "path" => TMPDIR . "cache/configs/", "extension" => ".cache"]);
        if (!$cache->isCached($cache_name)) {
            $dirManager = new DirManager();
            $cache->store($cache_name, $dirManager->getIni($path), 60*2);
        }
        $ini = $cache->retrieve($cache_name);

        $this->plugins->configs->$slug = new \stdClass();

        foreach($ini as $file){
            $tmp = basename($file);
            $tmp = str_replace(".ini", "", $tmp);
            $this->plugins->configs->$slug->$tmp = new Config($file);
        }
    }

    /**
     * Load Plugins Method
     */

    private function loadPlugins(){
        $dirManager = new DirManager();

        $cache = new Cache(["name" => "plugins", "path" => TMPDIR . "cache/plugins/", "extension" => ".cache"]);
        if (!$cache->isCached("plugins")) {
            $dirManager = new DirManager();
            $cache->store("plugins", $dirManager->getJson(APPDIR . "Plugin"), 60*5);
        }
        $plugin = $cache->retrieve("plugins");

        $lang = $this->configs->config->get("LANGUAGE_CODE");
		$translate = new Translate();
        foreach ($plugin as $file){

            $cache_name = "plugin_" . $file;
            $cache = new Cache(["name" => $cache_name, "path" => TMPDIR . "cache/plugins/", "extension" => ".cache"]);
            if (!$cache->isCached($cache_name)) {
                $cache->store($cache_name, json_decode(file_get_contents($file), false), 60*5);
            }
            $data = $cache->retrieve($cache_name);

            $slug = $data->slug;
            if(@!is_null($data->translate->supported->$lang)){
                $this->events->raise(new PluginLoadEvent($file, $data, $slug));
                $this->plugins->available->$slug = $data;
                if(@$data->customConfig){
                    $this->loadConfigInFolder(APPDIR . "Plugin" . DS . $slug . DS . "Config", $slug);
                }
                $this->loadRoutesInFolder(APPDIR . "Plugin" . DS . $slug . DS . "Route");
				if($data->haveCustomRessources){
					$this->getCustomRessources($slug);
				}
				if($data->permissions != null){
					foreach($data->permissions as $permission){
						if($permission->key != null){
							$content = "";
							if(!$permission->text->translate || $permission->text->translate == null){
								$content = $permission->text->content;
							}else{
								$translate->load(["file" => $permission->text->translate->file, "plugin" => $slug]);
								$content = $translate->element($permission->text->translate->path);
							}
							$this->permissions->list["plugins"][$slug][] = [
								"key" => $permission->key,
								"content" => $content
							];
						}
					}
				}
				if($data->eventListeners){
					$this->loadListeners(APPDIR . "Plugin" . DS . $slug . DS . "Listeners");
				}
            }else{
                $this->plugins->unavailable->$slug = $data;
            }
        }
    }

    /**
     * Get Custom Ressources of a Plugin
     *
     * @param string $plugin
     */

	private function getCustomRessources(string $plugin){
		$dirManager = new DirManager();
		$files = $dirManager->listFiles(APPDIR . "Plugin" . DS . $plugin . DS . "Ressources");
		foreach ($files as $file){
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			$types = array(
				'gif',
				'jpg',
				'jpeg',
				'png',
				'jpe',
				'css',
				'js'
			);
			if(in_array($ext, $types)){
				$this->customRessources[] = [
					"path" => $file,
					"slug" => $plugin . substr($file, strlen(APPDIR . "Plugin" . DS . $plugin . DS . "Ressources"))
				];
			}
		}
	}
}

