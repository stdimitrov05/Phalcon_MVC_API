<?php

/*============================
Frontend
=============================*/

$frontendCollection = new \Phalcon\Mvc\Micro\Collection();
$frontendCollection->setPrefix(API_VERSION);
$frontendCollection->setHandler('\App\Controllers\FrontendController', true);

$frontendCollection->get(
    '/',
    'indexAction'
);
$frontendCollection->get(
    '/home',
    'homeAction'
);

$frontendCollection->get(
    '/users/list',
    'getUsers'
);
$frontendCollection->get(
    '/users/id',
    'getUsersByID'
);

$app->mount($frontendCollection);


/*============================
User Profile
=============================*/
$profileCollection = new \Phalcon\Mvc\Micro\Collection();
$profileCollection->setPrefix(API_VERSION);
$profileCollection->setHandler('\App\Controllers\ProfileController', true);


$profileCollection->get(
    '/users',
    'getUsersAction'
);
$profileCollection->post(
    '/users',
    'createUserAction'
);


$app->mount($profileCollection);



/*============================
Products
=============================*/

$productCollection = new \Phalcon\Mvc\Micro\Collection();
$productCollection->setPrefix(API_VERSION . "/products");
$productCollection->setHandler('\App\Controllers\ProductController', true);

//Select
$productCollection->get(
    '/',
    'listAction'
);
//Insert
$productCollection->post(
    '/',
    'createAction'
);
//Select with pram id => id
$productCollection->get(
    '/{id:[1-9][0-9]*}',
    'detailsAction'
);
$productCollection->get(
    '/delete/{id:[1-9][0-9]*}',
    'deleteAction'
);
//Update
$productCollection->put(
    '/{id:[1-9][0-9]*}',
    'updateAction'
);



$app->mount($productCollection);

// Not found URLs
$app->notFound(
    function () use ($app) {
        $exception =
            new \App\Exceptions\HttpExceptions\Http404Exception(
                'URI not found or error in request.',
                \App\Controllers\AbstractController::ERROR_NOT_FOUND,
                new \Exception('URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI())
            );
        throw $exception;
    }
);
