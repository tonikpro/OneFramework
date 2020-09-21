<?php
namespace etc\components;

class Pgsql
{
	private $dbh;

	public function __construct($config)
	{
		$this->dbh = new \PDO($config["dsn"], $config["username"], $config["password"]);
		$this->dbh->exec('set names ' . $config['charset']);
	}

	public function execute($sql, $data = [])
	{
		$args = [];
		foreach($data as $key => $value)
		{
			// var_dump($value);
			switch(gettype($value))
			{
				case 'boolean':
					$args[] = [
						'name' => $key,
						'value' => $value,
						'type' => \PDO::PARAM_BOOL
					];
					break;
				case 'NULL':
					$args[] = [
						'name' => $key,
						'value' => null,
						'type' => \PDO::PARAM_NULL
					];
					break;
				case 'integer':
					$args[] = [
						'name' => $key,
						'value' => $value,
						'type' => \PDO::PARAM_INT
					];
					break;
				case 'array':
					$new_key = '';	
					foreach($value as $v){
						$i = md5(microtime());
						$new_key = sprintf('%s, :%s', $new_key, $i);
						$args[] = [
							'name' => $i,
							'value' => $v,
							'type' => \PDO::PARAM_STR
						];
					}
					$sql = str_replace(':' . $key, $new_key, $sql);
					break;
				default:
					$args[] = [
						'name' => $key,
						'value' => $value,
						'type' => \PDO::PARAM_STR
					];
					break;
			}
		}
		$stmt = $this->dbh->prepare($sql);
		foreach($args as $arg){
			$stmt->bindParam($arg['name'], $arg['value'], $arg['type']);
		}

		if($stmt->execute() === false)
		{
			throw new \Exception("Incorrect query: " . $stmt->queryString . " " . print_r($stmt->errorInfo(), true));
		}
		return $stmt;
	}

	public function all($sql, $params = [])
	{
		$stmt = $this->execute($sql, $params);
		return $stmt->fetchAll(\PDO::FETCH_OBJ);
	}

	public function one($sql, $params = [])
	{
		$stmt = $this->execute($sql, $params);
		// var_dump($sql);
		$result = $stmt->fetch(\PDO::FETCH_OBJ);
		if(!$result){
			return null;
		}
		return $result;
	}

	public function begin()
	{
		$this->dbh->beginTransaction();
	}

	public function commit()
	{
		$this->dbh->commit();
	}

	public function cancel()
	{
		$this->dbh->rollBack();
	}

	public function error()
	{
		return $this->dbh->errorInfo();
	}
}
?>