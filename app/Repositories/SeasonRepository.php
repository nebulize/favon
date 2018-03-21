<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Season;
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
     * @return Season
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : Season
    {
        $query = $this->season->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a season by supplied parameters.
     *
     * @param array $parameters
     * @return Season
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : Season
    {
        $query = $this->season;
        if (isset($parameters['year'])) {
            $query = $query->where('year', $parameters['year']);
        }
        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }
        // Filter by date
        if (isset($parameters['date'])) {
            $query = $query
                ->where('start_date', '<=', $parameters['date'])
                ->where('end_date', '>=', $parameters['date']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all Seasons.
     *
     * @param array $parameters
     * @return mixed
     */
    public function index(array $parameters = [])
    {
        $query = $this->season;
        if (isset($parameters['around'])) {
            $season = $parameters['around'];
            $query = $query->whereBetween('year', [$season->year - 1, $season->year + 1])->orderBy('start_date', 'ASC');
            $seasons = $query->get();
            $before = $seasons->filter(function ($item) use ($season) {
                return $item->start_date->lt($season->start_date);
            });
            $after = $seasons->filter(function ($item) use ($season) {
                return $item->start_date->gt($season->start_date);
            });

            return [
                'before' => $before->take(-2),
                'after' => $after->take(1),
            ];
        }

        return $query->get();
    }

    /**
     * Create a season with the supplied attributes.
     *
     * @param array $attributes
     * @return Season
     */
    public function create(array $attributes) : Season
    {
        if (isset($attributes['date'])) {
            $date = $attributes['date'];
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

        return $this->season->create($attributes);
    }

    /**
     * Delete an existing season.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing Season.
     *
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function update(Model $model, array $attributes) : Model
    {
        $model->fill($attributes);
        $model->save();

        return $model;
    }
}
