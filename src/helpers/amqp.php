<?php
namespace nx\helpers\queue;

use PhpAmqpLib\Channel\AMQPChannel;

class amqp{
	protected $config=[];
	protected $conn=null;
	protected $channel =null;
	public function __construct($config){
		$this->config=$config;
	}
	protected function channel():AMQPChannel{
		if(null === $this->conn){
			$this->conn=new \PhpAmqpLib\Connection\AMQPStreamConnection(...$this->config['conn']);
			$this->channel =$this->conn->channel();
		}
		return $this->channel;
	}
	protected function declareRoute($key):void{
		$config=$this->config['route'][$key] ?? null;
		if(null === $config) throw new \Error('未定义的route配置 '.$key);
		if($config['declare']){
			$this->channel()
				->exchange_declare($config['exchange']['name'], $config['exchange']['type'] ?? 'direct', $config['exchange']['passive'] ?? false, $config['exchange']['durable'] ?? true,
					$config['exchange']['auto_delete'] ?? false);
			$this->channel()
				->queue_declare($config['queue']['name'], $config['queue']['passive'] ?? false, $config['queue']['durable'] ?? false, $config['queue']['exclusive'] ?? true,
					$config['queue']['auto_delete'] ?? false);
			$this->channel()->queue_bind($config['queue']['name'], $config['exchange']['name'], $key);
		}
	}
	public function publish($key, $data):bool{
		try{
			$this->declareRoute($key);

			$_data =is_string($data) ?$data :json_encode($data, JSON_UNESCAPED_UNICODE);
			$msg=new \PhpAmqpLib\Message\AMQPMessage($_data, ["delivery_mode"=>\PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_NON_PERSISTENT]);
			$this->channel()->basic_publish($msg, $this->config['route'][$key]['exchange']['name'], $key);
			return true;
		} catch(\Exception $e){
			return false;
		}
	}
}