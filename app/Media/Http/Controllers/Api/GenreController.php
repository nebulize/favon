<?php

namespace Favon\Media\Http\Controllers\Api;

use Favon\Application\Http\Controller;
use Favon\Media\Repositories\GenreRepository;

class GenreController extends Controller
{
    /**
     * @var GenreRepository
     */
    protected $genreRepository;

    /**
     * GenreController constructor.
     * @param GenreRepository $genreRepository
     */
    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * Get a list of all genres.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $genres = $this->genreRepository->index([
            'orderBy' => ['name', 'ASC'],
        ]);

        return response()->json($genres);
    }
}
