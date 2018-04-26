<?php
namespace Event;

/**
 * Class EventPublisher
 * @package Event
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class EventPublisher {

    /**
     * Return an Instance of EventPublisher
     * @var EventPublisher
     */
	private static $instance;

    /**
     * Return Listeners
     * @var array
     */
	private $listeners = [];

    /**
     * Get Instance of EventPublisher
     *
     * @return EventPublisher
     */
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new EventPublisher();
		}
		
		return self::$instance;
	}

    /**
     * Raise an Event
     *
     * @param $event
     */

	public function raise($event){
		$class = get_class($event);
		if($this->hasListener($class)){
			foreach($this->listeners[$class] as $listener){
				call_user_func($listener, $event);
			}
		}
	}

    /**
     * Listen an Event
     *
     * @param $event
     * @param $callable
     */

	public function listen($event, $callable){
		if(!$this->hasListener($event)){
			$this->listeners[$event] = [];
		}
		$this->listeners[$event][] = $callable;
	}

    /**
     * Has Listener Method
     *
     * @param $event
     * @return bool
     */

	private function hasListener($event) : bool {
		return array_key_exists($event, $this->listeners);
	}
}