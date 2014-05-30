<?php
/**
 * Core Cahe Memcache
 * 
 * - verifique se você tem instalado o módulo php5-memcached e memcached
 *
 * @package		Core
 * @subpackage	Core.Cache
 * @see 		http://www.php.net/manual/pt_BR/class.memcached.php
 */
class Memcache {
	/**
	 * Inicia o objeto Memcache
	 *
	 * @param 	array 	$conf 	Matriz com os dados de configuração do memcached
	 * @return 	void
	 */
	public function __construct($conf = array())
	{
		$this->config = $conf;
		
		$this->config['compress'] = isset($conf['compress']) 
		 ? $conf['compress'] 	: false;

		$this->config['persistent'] = isset($conf['persistent'])
		 ? $conf['persistent'] 	: true;

		$this->config['servers'] = isset($conf['servers'])
		 ? $conf['servers'] 	: array('host'=>'127.0.0.1','port'=>11211);

		// inicia o memcache
		if (extension_loaded('memcached'))
		{
			$this->cache = new Memcached();
			foreach ($this->config as $_cmp => $_prop)
			{
				$this->cache->addServer($_prop['host'], $_prop['port'], $this->config['persistent']);
			}
			$this->on = true;
		} else
		{
			$this->cache = null;
			$this->on = false;
		}
	}

	/**
	 * Cria uma novo cache
	 *
	 * @param string 	$chave 		Nome da chave
	 * @param mixed 	$valor 		Valor a ser cacheado
	 * @param integer 	$duracao 	Tempo de duração do cache em segundos
	 * @param return 	boolean 	Retorna verdadeiro se o cache foi criado, falso se falhou.
	 */
	public function write($chave, $valor, $duracao=3600)
	{
		$data = ($this->on) ? $this->cache->set($chave, $valor, $duracao) : null;
		return $data;
	}

	/**
	 * Retorna o valor de cache pela sua chave
	 *
	 * @param 	string 	$chave 	Nome da chave
	 * @return 	mixed 	Valor do cache, ou Falso, caso o cache não existe mais, ou não existe.
	 */
	public function read($chave)
	{
		$data = ($this->on) ? $this->cache->get($chave) : null;
		return $data;
	}

	/**
	 * Exclui um cache existente
	 *
	 * @param 	string 	$chave 	Nome da chave ser excluída
	 * @return 	boolean Verdadeiro se o cache foi deltado, caso contrário Falso.
	 */
	public function delete($chave)
	{
		$data = ($this->on) ? $this->cache->delete($chave) : null;
		return $data;
	}

	/**
	 * Exclui todas os caches
	 *
	 * @return 	boolean 	Retorna Verdadeiro em caso de sucesso, Falso se não
	 */
	public function clear() 
	{
		$data = ($this->on) ? $this->cache->flush() : null;
		return $data;
	}

	/**
	 * Retorna um array com os status do memcache
	 *
	 * return array 
	 */
	public function info()
	{
		$data = ($this->on) ? $this->cache->getstats() : null;
		return $data;
	}

}
