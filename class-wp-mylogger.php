<?php
//include_once(plugin_dir_path( __FILE__ ) .'/log4php/Logger.php');
require_once(ABSPATH.'wp-content/plugins/wp-mylogger/Entity/MyLog.php');


/**
 * Plugin name.
 *
 * @package   WP My Logger
 * @author    Nicolò Palmigiano
 * @copyright 2015 PaNiko
 *
/**
 * Plugin class.
 *
 * @package My_Logger
 * @author PaNiko
 */
class My_Logger {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 * @since   1.0.0
	 * @var     string
	 */
	protected $version = '1.0.0';
	protected $loggerObject;
	protected $logger;
	protected $nameLogger;
	protected $typeLogger;
	protected $parametersLogger;
	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'wp-mylogger';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	
	const DAILY 	= "LoggerAppenderDailyFile";
	const ROLLING 	= "LoggerAppenderRollingFile";
	const FILE 		= "LoggerAppenderFile";
	const MAIL		= "LoggerAppenderMail";
	const MAILEVENT	= "LoggerAppenderMailEvent";
	//TODO: new appender
//	const MONGODB 	= "LoggerAppenderMongoDB";
// 	LoggerAppenderNull
// 	LoggerAppenderPDO
// 	LoggerAppenderPhp
// 	LoggerAppenderSocket
// 	LoggerAppenderSyslog

	//DAILY: parameters 
	const DAILY_FILE				= "file";
	const DAILY_APPENDER			= "appender";
	const DAILY_DATEPATTERN			= "datePattern";
	//ROLLING: parameters
	const ROLLING_FILE				= "file";
	const ROLLING_APPENDER			= "appender";
	const ROLLING_DATEPATTERN		= "datePattern";
	const ROLLING_MAX_FILE_SIZE		= "maxFileSize";
	const ROLLING_MAX_BACKUP_INDEX	= "maxBackupIndex";
	const ROLLING_COMPRESS			= "compress";
	//MAIL: parameters
	const MAIL_TO					= "to";
	const MAIL_FROM					= "from";
	const MAIL_SUBJECT				= "subject";
	
	//Logger threshold
	const THRESHOLD		= "THRESHOLD";
	const LEVEL_FATAL	= "FATAL";
	const LEVEL_ERROR	= "ERROR";
	const LEVEL_WARN	= "WARN";
	const LEVEL_INFO	= "INFO";
	const LEVEL_DEBUG	= "DEBUG";
	const LEVEL_TRACE	= "TRACE";
	const LEVEL_ALL		= "ALL";
	
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct($nameLogger = null, $type=null, $parameters=null) {
		$this->nameLogger = $nameLogger;
		$this->typeLogger = $type;
		$this->parametersLogger = $parameters;
		
		add_action( 'init_mylogger', array($this, 'init_mylogger'),10,3);
		//add_action( 'init', array( $this, 'init' ) );
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		// Add the options page and menu item.
		// add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		do_action('init_mylogger', $this->nameLogger, $this->typeLogger, $this->parametersLogger);

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		//add_action( 'get_logger', array( $this, 'getLogger' ));
// 		add_filter( 'TODO', array( $this, 'filter_method_name' ) );
		
	}

	/**
	 * Return an instance of this class.
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance($nameLogger, $type, $parameters) {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self($nameLogger, $type, $parameters);
		}

		return self::$instance;
	}
	
	public function init(){
		do_action('init_mylogger', $this->nameLogger, $this->typeLogger, $this->parametersLogger);
	}
	
	/**
	 * Initi logger
	 * @param srting $nameLogger: name logger
	 * @param string $type: type of appender
	 * @param array $parameters: es: filesize, index
	 */
	public function init_mylogger($nameLogger, $type, $parameters){
		$this->loggerObject =  new MyLog($nameLogger, $type, $parameters);
		$this->logger = $this->loggerObject->getLogger();
	}
	
	/**
	 * Ritorna l'istanza del logger
	 * @return $log
	 */
	public function getLogger(){
		return $this->logger;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'RCS Logger', $this->plugin_slug ),
			__( 'RCS Logger', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

}