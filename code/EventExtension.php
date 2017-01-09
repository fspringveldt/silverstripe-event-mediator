<?php

	/**
	 * Class EventExtension
	 * @package
	 */
	class EventExtension extends Extension
	{
		/**
		 * @var EventMediator An injected Mediator
		 */
		protected $mediator;

		/**
		 * @return EventMediator
		 * @throws Exception
		 */
		public function getMediator()
		{
			if(!$this->mediator)
			{
				$this->mediator = Injector::inst()
										  ->get('EventMediator');
			}

			return $this->mediator;
		}

		/**
		 * @param      $eventName
		 * @param null $data
		 *
		 * @return bool
		 */
		public function emit($eventName, $data = NULL)
		{
			$this->getMediator()
				 ->trigger($eventName, $data);
			return true;

		}


	}

