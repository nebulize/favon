<?php

namespace App\Http\Controllers;

use App\Repositories\PersonRepository;

class BaseController extends Controller
{
    public function test(PersonRepository $personRepository)
    {
        return $personRepository->find([
            'tmdb_id' => 1975855,
        ]);
    }
}
