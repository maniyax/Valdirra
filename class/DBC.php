<?php
// главный класс работы с MySQL
define('DB_SERVER', 'localhost');	//хост
define('DB_USER', 'dbuser');			//юзер
define('DB_PASS', 'dbpass');	//пасс
define('DB_BASE', 'dbbase');			//база
define('DB_CHARSET', 'UTF8');		//кодировка
class DBC {

	private $_handle = null;
	private static $_instance = null;

	private function __construct() {
		$this->connect();
	}
	// синглтон. пример подключения: $db = DBC::instance();
	public static function instance() {
		if (self::$_instance == null)
			self::$_instance = new DBC();

		return self::$_instance;
	}
	// соединяемся с базой
	private function connect() {
		@$this->_handle = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_BASE);	// соединяемся
		if (mysqli_connect_error()) {
			exit('Ошибка соединения с базой данных, повторите через несколько секунд или обратитесь к администратору!');
		}
		$this->_handle->set_charset(DB_CHARSET);	// установим кодировку
	}
	// обычный запрос к базе
	public function query($q) {
		return $this->_handle->query($q);
	}
	
	public function insert_id() {
		return $this->_handle->insert_id;
	}
	
	public function real_escape_string($s) {
		return $this->_handle->real_escape_string($s);
	}
}

// сразу же соединимся
$db = DBC::instance();
?>