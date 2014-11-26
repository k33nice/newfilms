<?php

class AppFactory {
    public static $slimInstance;

    public static function initApp() {
        // Create monolog logger and store logger in container as singleton
        // (Singleton resources retrieve the same log resource definition each time)
        // Prepare app
        $app = new \Slim\Slim(array(
            'debug'          => true,
            'mode'           => 'testing',
            'templates.path' => __DIR__ . '/../templates'
        ));

        $app->container->singleton('log', function () use ($app) {
            $log = new \Monolog\Logger('itbox-dealer');

            $log->pushHandler(
                new \Monolog\Handler\StreamHandler( __DIR__ . '/../logs/app.log', \Monolog\Logger::DEBUG)
            );

            return $log;
        });

        // Prepare view
        $app->view(new \Slim\Views\Twig());
        $app->view->parserOptions = array(
            'charset'           => 'utf-8',
            'cache'             => false,
            'auto_reload'       => true,
            'strict_variables'  => true,
            'autoescape'        => true
        );
        $app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

        // Prepare middleware
        // $app->add(new \SlimJson\Middleware(array(
        //   'json.status'            => false,
        //   'json.override_error'    => false,
        //   'json.override_notfound' => false
        // )));

        // Define API routes
        $app->group('/api', function () use ($app) {
            $app->group('/films', function() use ($app) {
                $films = new \Controller\Films($app);
                $app->get('/', array($films, 'index'));
                $app->get('/:Id', array($films, 'show'))->name('films_show');
                $app->delete('/:Id', array($films, 'delete'))->name('films_delete');
                $app->post('/:Id', array($films, 'update'))->name('films_update');
                $app->post('/', array($films, 'create'))->name('films_create');
            });

            $app->group('/add', function() use ($app) {
                $add = new \Controller\Add($app);
                $app->get('/', array($add, 'index'))->name('adds_index');
                $app->get('/:AddId', array($add, 'show'))->name('adds_show');
                $app->delete('/:AddId', array($add, 'delete'))->name('adds_delete');
                $app->post('/:AddId', array($add, 'update'))->name('adds_update');
                $app->post('/', array($add, 'create'))->name('adds_create');
            });
        });

        // Define routes
        $app->get('/', function () use ($app) {
            // Sample log message
            // $app->log->info("Slim-Skeleton '/' route");
            // $films = new \Controller\Films($app);
            // $app->get('/', array($films, 'index'))->name('films_index');
            // $films = $app->request->getBody();
            // $films = $arr;
            // Render index view
            $app->render('index.html'/*, array('films' => $films)*/);
        });

        self::$slimInstance = $app;
    }

    /**
     * [initPropel description]
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    public function initPropel($config) {
        extract($config);

        // Autogenerated propel code
        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('engine', $driver);
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration(array (
          'dsn' => "$driver:host=$host;dbname=$database;charset=$charset;",
          'user' => $username,
          'password' => $password,
        ));
        $manager->setName('engine');
        $serviceContainer->setConnectionManager('engine', $manager);
        $serviceContainer->setDefaultDatasource('engine');
    }

}

?>