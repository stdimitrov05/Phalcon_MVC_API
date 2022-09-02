<?php

/*============================
Frontend
=============================*/

$profileCollection = new \Phalcon\Mvc\Micro\Collection();
$profileCollection->setPrefix(API_VERSION);
$profileCollection->setHandler('\App\Controllers\FrontendController', true);

$profileCollection->get(
    '/',
    'indexAction'
);

$app->mount($profileCollection);


/*============================
User Profile
=============================*/
$profileCollection = new \Phalcon\Mvc\Micro\Collection();
$profileCollection->setPrefix(API_VERSION. "/users");
$profileCollection->setHandler('\App\Controllers\ProfileController', true);

//Select
$profileCollection->get(
    '/',
    'listAction'
);
//Insert
$profileCollection->post(
    '/',
    'createAction'
);
//Select with pram id => id
$profileCollection->get(
    '/{id:[1-9][0-9]*}',
    'detailsAction'
);
$profileCollection->get(
    '/delete/{id:[1-9][0-9]*}',
    'deleteAction'
);
//Update
$profileCollection->put(
    '/{id:[1-9][0-9]*}',
    'updateAction'
);



$app->mount($profileCollection);



/*============================
Products
=============================*/

$profileCollection = new \Phalcon\Mvc\Micro\Collection();
$profileCollection->setPrefix(API_VERSION . "/products");
$profileCollection->setHandler('\App\Controllers\ProductController', true);

//Select
$profileCollection->get(
    '/',
    'listAction'
);
//Insert
$profileCollection->post(
    '/',
    'createAction'
);
//Select with pram id => id
$profileCollection->get(
    '/{id:[1-9][0-9]*}',
    'detailsAction'
);
$profileCollection->get(
    '/delete/{id:[1-9][0-9]*}',
    'deleteAction'
);
//Update
$profileCollection->put(
    '/{id:[1-9][0-9]*}',
    'updateAction'
);



$app->mount($profileCollection);

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
