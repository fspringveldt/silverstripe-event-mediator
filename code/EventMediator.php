<?php

	namespace eventMediator;
		/**
		 * Created by PhpStorm.
		 * User: francospringveldt
		 * Date: 2016/12/31
		 * Time: 10:01 PM
		 */
	use AfterCallAspect;
	use Injector;

	/**
	 * Class EventMediator
	 * @package
	 *
	 * This class employs the Mediator design pattern, which is called after methods defined in
	 * the yaml config
	 */
	class EventMediator implements AfterCallAspect
	{
		public $events = [];

		public function attach($eventName, $callback)
		{
			if(!isset($this->events[$eventName]))
			{
				$this->events[$eventName] = [];
			}
			$this->events[$eventName][] = $callback;
		}

		/**
		 * @param      $eventName
		 * @param null $data
		 */
		public function trigger($eventName, &$data = NULL)
		{
			foreach($this->events[$eventName] as $name => $obj)
			{
				$observer = Injector::inst()
									->create($obj['class']);
				call_user_func_array(
					[
						$observer,
						$obj['method'],
					], [$data]
				);
			}
		}

		/**
		 * Call this aspect after a method is executed
		 *
		 * @param object $proxied
		 *                The object having the method called upon it.
		 * @param string $method
		 *                The name of the method being called
		 * @param string $args
		 *                The arguments that were passed to the method call
		 * @param mixed  $result
		 *                The result of calling the method on the real object
		 */
		public function afterCall($proxied, $method, $args, $result)
		{
			$this->trigger($method, $args[0]);
		}
	}