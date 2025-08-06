<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\controllers\BookController;

(new BookController()) -> handle(); 


