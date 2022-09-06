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

//Select all Users
$profileCollection->get(
    '/',
    'listAction'
);
//Register new User
$profileCollection->post(
    '/signup',
    'createAction'
);
//Select User by id
$profileCollection->get(
    '/{id:[1-9][0-9]*}',
    'detailsAction'
);
//Confirm User email
$profileCollection->post(
    '/email/confirm',
    'emailAction'
);
//Delete User by id
$profileCollection->get(
    '/delete/{id:[1-9][0-9]*}',
    'deleteAction'
);

//Update User by id
$profileCollection->put(
    '/{id:[1-9][0-9]*}',
    'updateAction'
);



$app->mount($profileCollection);



/*============================
Products
=============================*/

$productCollection = new \Phalcon\Mvc\Micro\Collection();
$productCollection ->setPrefix(API_VERSION . "/products");
$productCollection ->setHandler('\App\Controllers\ProductController', true);

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

/*============================
Tokens
=============================*/

$tokensCollection = new \Phalcon\Mvc\Micro\Collection();
$tokensCollection ->setPrefix(API_VERSION . "/tokens");
$tokensCollection ->setHandler('\App\Controllers\TokenController', true);

//Select
$tokensCollection->get(
    '/',
    'listAction'
);

//Select with pram id => id
$tokensCollection->get(
    '/{id:[1-9][0-9]*}',
    'detailsAction'
);

//Insert
$tokensCollection->post(
    '/',
    'createAction'
);

//Delete
$tokensCollection->get(
    '/delete',
    'deleteAction'
);

$app->mount($tokensCollection);



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
