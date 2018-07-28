<?php

namespace Favon\Media\Repositories;

use Carbon\Carbon;
use Favon\Application\Interfaces\RepositoryContract;
use Favon\Media\Models\Season;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeasonRepository implements RepositoryContract
{
    /**
     * Season ORM.
     * @var Season
     */
    protected $season;

    /**
     * SeasonRepository constructor.
     * @param Season $season
     */
    public function __construct(Season $season)
    {
        $this->season = $season;
    }

    /**
     * Fetch a Season by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Season
     */
    public function get(int $id, array $parameters = []): Season
    {
        $query = $this->season->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a season by supplied parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Season
     */
    public function find(array $parameters = []): Season
    {
        $query = $this->season->newQuery();

        // Filter by year
        if (isset($parameters['year'])) {
            $query = $query->where('year', $parameters['year']);
        }

        // Filter by name
        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        // Filter by date
        if (isset($parameters['date'])) {
            $date = $parameters['date']->copy();
            $query = $query->where('start_date', '<=', $date);

            // Overflow into the next season if tv season start_date is very close to the season end_date
            if (isset($parameters['overflow']) && $parameters['overflow'] === true) {
                $date = $date->addDays(14);
            }
            $query = $query->where('end_date', '>=', $date);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all Seasons.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->season->newQuery();

        return $query->get();
    }

    /**
     * Get a list of seasons around a specified date.
     *
     * @param Season $season
     *
     * @return array
     */
    public function indexAround(Season $season): array
    {
        $query = $this->season->newQuery()
            ->whereBetween('year', [$season->year - 1, $season->year + 1])
            ->orderBy('start_date', 'ASC');
        $seasons = $query->get();
        $before = $seasons->filter(function ($item) use ($season) {
            return $item->start_date->lt($season->start_date);
        });
        $after = $seasons->filter(function ($item) use ($season) {
            return $item->start_date->gt($season->start_date);
        });

        return [
            'before' => $before->take(-2),
            'after' => $after->take(2),
        ];
    }

    /**
     * Create a season with the supplied attributes.
     *
     * @param array $attributes
     *
     * @return Season
     */
    public function create(array $attributes): Season
    {
        if (isset($attributes['date'])) {
            // Need to create a copy here since otherwise addDays will update the original date as well.
            $date = $attributes['date']->copy();

            // Overflow into the next season if tv season start_date is close to season end_date
            if (isset($attributes['overflow']) && $attributes['overflow'] === true) {
                $date = $date->addDays(14);
            }

            $year = $date->year;
            $month = $date->month;
            if (\in_array($month, [1, 2, 3], true)) {
                $start_date = Carbon::create($year, 1, 1);
                $end_date = $start_date->copy()->addMonth(2)->endOfMonth();
                $name = 'Winter';
            } elseif (\in_array($month, [4, 5, 6], true)) {
                $start_date = Carbon::create($year, 4, 1);
                $end_date = $start_date->copy()->addMonth(2)->endOfMonth();
                $name = 'Spring';
            } elseif (\in_array($month, [7, 8, 9], true)) {
                $start_date = Carbon::create($year, 7, 1);
                $end_date = $start_date->copy()->addMonth(2)->endOfMonth();
                $name = 'Summer';
            } else {
                $start_date = Carbon::create($year, 10, 1);
                $end_date = $start_date->copy()->addMonth(2)->endOfMonth();
                $name = 'Fall';
            }
            $attributes['start_date'] = $start_date;
            $attributes['end_date'] = $end_date;
            $attributes['name'] = $name;
            $attributes['year'] = $year;
        }

        return $this->season->newQuery()->create($attributes);
    }

    /**
     * Delete an existing season.
     *
     * @param Model $model
     * l
     * @throws \Exception
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing Season.
     *
     * @param Model $model
     * @param array $attributes
     */
    public function update(Model $model, array $attributes): void
    {
        $model->fill($attributes);
        $model->save();
    }
}
