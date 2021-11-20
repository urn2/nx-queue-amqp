<?php
namespace nx\parts\queue;

trait amqp{
	protected $queue_amqp=[];//缓存
	/**
	 * @param string $name app->setup['queue/amqp']
	 * @return \nx\helpers\queue\amqp
	 */
	public function queue(string $name='default'):?\nx\helpers\queue\amqp{
		if(!array_key_exists($name, $this->queue_amqp)){
			$config =($this->setup['queue/amqp'] ??[])[$name] ?? null;
			if(null ===$config){
				$this->throw(500, "queue[{$name}] config error.");
			}
			$this->queue_amqp[$name]= new \nx\helpers\queue\amqp($config);
		}
		return $this->queue_amqp[$name];
	}
}