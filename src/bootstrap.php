<?php

use nqs\render;
use nqs\database;
use nqs\router;
use nqs\view;
use nqs\config;
use nqs\pluginManager;

pluginManager::preinit();

config::init();

router::init(config::getRoutes());
render::init(config::getRender());

pluginManager::init();

$route = router::getRoute();

pluginManager::postinit();

$_view;

if($route == null)
    $_view = new view(config::get404());
else
    $_view = new view($route);

$_view->render();
