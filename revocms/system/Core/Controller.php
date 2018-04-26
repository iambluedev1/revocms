<?php
namespace Core;

use Web\Url;
use RevoCMS\RevoCMS;
use Core\View;
use Session\Session;
use Cookie\Cookie;
use Core\Translate;
use Web\Request;
use Usage\Flash;
use Mail\Mail;
use Usage\Geolocation;
use Usage\Number;

/**
 * Class Controller
 * @package Core
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

abstract class Controller
{
    /**
     * Return Url Class
     *
     * @var Url
     */
    public $url;

    /**
     * Return RevoCMS Class
     *
     * @var RevoCMS
     */
	public $rcms;

    /**
     * Return View Class
     *
     * @var View
     */
	public $view;

    /**
     * Return Session Class
     *
     * @var Session
     */
	public $session;

    /**
     * Return Cookie Class
     *
     * @var Cookie
     */
	public $cookie;

    /**
     * Return Translate Class
     *
     * @var Translate
     */
	public $translate;

    /**
     * Return Flash Class
     *
     * @var Flash
     */
	public $flash;

    /**
     * Return Request Class
     *
     * @var Request
     */
	public $request;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if(strtolower(str_replace("Controller", "",  substr(get_class($this), strrpos(get_class($this), '\\') + 1))) != "soon"){
            $this->restrict();
        }else{
            //var_dump($_SERVER["REMOTE_ADDR"]);
        }
        $this->url = new Url();
		$this->rcms = RevoCMS::getInstance();
		$this->translate = new Translate(strtolower(str_replace("Controller", "",  substr(get_class($this), strrpos(get_class($this), '\\') + 1))));
		$this->view = new View();
		$this->session = new Session();
		$this->cookie = new Cookie();
        $this->flash = Flash::getInstance();
        $this->request = new Request();
        $this->request->setController(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
		$this->session->write("init", true);

		$this->security_check();

    }

    /**
     * Load Model
     *
     * @param string|array $name
     */
	function loadModel($name){
        if(is_array($name)){
            foreach ($name as $c){
                $segments = explode("\\", get_class($this));
                $slug = ($segments[1] == "Plugin") ? "App\\Plugin\\" . $segments[2] . "\\Model": "App\Model";
                $class = $slug . "\\$c";
                $this->$c = @new $class();
            }
        }else if(!isset($this->$name)){
			$class = "App\Model\\$name";
			$this->$name = new $class();
		}
	}

    /**
     * Display Error Page
     */
	function e404(){
	    $this->view->set(["title" => "Erreur 404"]);
	    $this->view->render(["folder" => "error", "file" => "index"]);
    }

    /**
     * Restrict Method
     */
    function restrict(){
        $allowed_ip = array("77.207.236.237", "83.202.91.228", "95.171.137.15");
        if(!in_array($_SERVER["REMOTE_ADDR"], $allowed_ip)){
            header('Location: http://cs.vulkan-mc.fr');
            exit;
        }
    }

    /**
     * Security Method
     */
    private function security_check()
    {
        $allowed_routes = array("logout", "ajax/security/redeem", "ajax/security/email", "ajax/security/2auth");
        if (Session::readUser("token") != "" && !in_array(Url::detectUri(), $allowed_routes)) {

            $this->loadModel("Security");

            $geo = new Geolocation();
            $id = Session::readUser("id");

            $security["login.email"] = $this->Security->getRule("login.email", $id);
            $security["login.ip"] = $this->Security->getRule("login.ip", $id);
            $security["login.ip.value"] = $this->Security->getRule("login.ip.value", $id);
            $security["login.2auth"] = $this->Security->getRule("login.2auth", $id);
            $security["login.2auth.secret"] = $this->Security->getRule("login.2auth.secret", $id);

            if ($security["login.ip"] == "true" && !Session::readUser("verif_ip")) {
                if($geo->getIp() != $security["login.ip.value"]){
                    $this->flash->add("error", "Vous n'êtes pas autorisé à vous connecter à ce compte !");
                    Url::redirect("logout");
                    exit();
                }else{
                    Session::writeUser("verif_ip", true);
                }
            }

            if ($security["login.2auth"] == "true" && $security["login.2auth.secret"] != "" && !Session::readUser("verif_2auth")) {
                $this->view->js(["page/security.page.js?v=" . time()]);
                $this->view->set(["title" => "Double Authentification"]);
                $this->view->render(["folder" => "user", "file" => "security_2auth"]);
                exit();
            }

            if ($security["login.email"] == "true" && !Session::readUser("verif_email")) {
                if(Session::readUser("verif_email_token") == ""){
                    Session::writeUser("verif_email_token", Number::createkey(10));
                }

                if(!Session::readUser("verif_email_sent")){
                    Session::writeUser("verif_email_sent", true);

                    $mail = new Mail();
                    $mail->subject("Token de sécurité");
                    $mail->body("Voici le token : " . Session::readUser("verif_email_token"));
                    $mail->addAddress(Session::readUser("email"), Session::readUser("username"));
                    $mail->send();
                }

                $this->view->js(["page/security.page.js?v=" . time()]);
                $this->view->set(["title" => "Vérification par email"]);
                $this->view->render(["folder" => "user", "file" => "security_email"]);
                exit();
            }
        }
    }
}
