<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Services\Tmdb\Client;

use JsonException;
use App\Enums\Occupations;
use App\Services\Tmdb\TMDB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TV
{
    /**
     * @var array{
     *     adult: ?bool,
     *     backdrop_path: ?string,
     *     created_by: ?array<
     *         int<0, max>,
     *         array{
     *             id: ?int,
     *             credit_id: ?string,
     *             name: ?string,
     *             gender: ?int,
     *             profile_path: ?string,
     *         },
     *     >,
     *     episode_run_time: ?array<int>,
     *     first_air_date: ?string,
     *     genres: ?array<
     *         int<0, max>,
     *         array{
     *             id: ?int,
     *             name: ?string,
     *         },
     *     >,
     *     homepage: ?string,
     *     id: ?int,
     *     in_production: ?bool,
     *     languages: ?array<string>,
     *     last_air_date: ?string,
     *     last_episode_to_air: ?array{
     *         id: ?int,
     *         name: ?string,
     *         overview: ?string,
     *         vote_average: ?float,
     *         vote_count: ?int,
     *         air_date: ?string,
     *         episode_number: ?int,
     *         production_code: ?string,
     *         runtime: ?int,
     *         season_number: ?int,
     *         show_id: ?int,
     *         still_path: ?string,
     *     },
     *     name: ?string,
     *     next_episode_to_air: ?string,
     *     networks: ?array<
     *         int<0, max>,
     *         array{
     *             id: ?int,
     *             logo_path: ?string,
     *             name: ?string,
     *             origin_country: ?string,
     *         },
     *     >,
     *     number_of_episodes: ?int,
     *     number_of_seasons: ?int,
     *     origin_country: ?array<string>,
     *     original_language: ?string,
     *     original_name: ?string,
     *     overview: ?string,
     *     popularity: ?float,
     *     poster_path: ?string,
     *     production_companies: ?array<
     *         int,
     *         array{
     *             id: int,
     *             logo_path: ?string,
     *             name: ?string,
     *             origin_country: ?string,
     *         },
     *     >,
     *     production_countries: ?array<
     *         int<0, max>,
     *         array{
     *             iso_3166_1: ?string,
     *             name: ?string,
     *         },
     *     >,
     *     seasons: ?array<
     *         int<0, max>,
     *         array{
     *             air_date: ?string,
     *             episode_count: ?int,
     *             id: ?int,
     *             name: ?string,
     *             overview: ?string,
     *             poster_path: ?string,
     *             season_number: ?int,
     *             vote_average: ?int,
     *         }
     *     >,
     *      spoken_languages: ?array<
     *          int<0, max>,
     *          array{
     *              english_name: ?string,
     *              iso_639_1: ?string,
     *              name: ?string,
     *          },
     *      >,
     *     status: ?string,
     *     tagline: ?string,
     *     type: ?string,
     *     vote_average: ?float,
     *     vote_count: ?int,
     *     aggregate_credits: ?array{
     *         cast: ?array<
     *             int<0, max>,
     *             array{
     *                 adult: ?bool,
     *                 gender: ?int,
     *                 id: ?int,
     *                 known_for_department: ?string,
     *                 name: ?string,
     *                 original_name: ?string,
     *                 popularity: ?float,
     *                 profile_path: ?string,
     *                 roles: ?array<
     *                     int<0, max>,
     *                     array{
     *                         credit_id: ?string,
     *                         character: ?string,
     *                         episode_count: ?int,
     *                     },
     *                 >,
     *                 total_episode_count: ?int,
     *                 order: ?int,
     *             },
     *         >,
     *         crew: ?array<
     *             int<0, max>,
     *             array{
     *                 adult: ?bool,
     *                 gender: ?int,
     *                 id: ?int,
     *                 known_for_department: ?string,
     *                 name: ?string,
     *                 original_name: ?string,
     *                 popularity: ?float,
     *                 profile_path: ?string,
     *                 jobs: ?array<
     *                     int<0, max>,
     *                     array{
     *                         credit_id: ?string,
     *                         job: ?string,
     *                         episode_count: ?int,
     *                     },
     *                 >,
     *                 department: ?string,
     *                 total_episode_count: ?int,
     *             },
     *         >,
     *         id: ?int,
     *     },
     *     videos: ?array{
     *         id: ?int,
     *         results: ?array<
     *             int<0, max>,
     *             ?array{
     *                 iso_639_1: ?string,
     *                 iso_3166_1: ?string,
     *                 name: ?string,
     *                 key: ?string,
     *                 site: ?string,
     *                 size: ?string,
     *                 type: ?string,
     *                 official: ?bool,
     *                 published_at: ?string,
     *                 id: ?string,
     *             },
     *         >,
     *     },
     *     images: ?array{
     *         backdrops: ?array<
     *             int<0, max>,
     *             array{
     *                 aspect_ratio: ?float,
     *                 height: ?int,
     *                 iso_639_1: ?string,
     *                 file_path: ?string,
     *                 vote_average: ?float,
     *                 vote_count: ?int,
     *                 width: ?int,
     *             },
     *         >,
     *         id: ?int,
     *         logos: ?array<
     *              int<0, max>,
     *              array{
     *                  aspect_ratio: ?float,
     *                  height: ?int,
     *                  iso_639_1: ?string,
     *                  file_path: ?string,
     *                  vote_average: ?float,
     *                  vote_count: ?int,
     *                  width: ?int,
     *              },
     *          >,
     *         posters: ?array<
     *              int<0, max>,
     *              array{
     *                  aspect_ratio: ?float,
     *                  height: ?int,
     *                  iso_639_1: ?string,
     *                  file_path: ?string,
     *                  vote_average: ?float,
     *                  vote_count: ?int,
     *                  width: ?int,
     *              },
     *          >,
     *     },
     *     external_ids: ?array{
     *         id: ?int,
     *         imdb_id: ?string,
     *         freebase_mid: ?string,
     *         freebase_id: ?string,
     *         tvdb_id: ?int,
     *         tvrage_id: ?int,
     *         wikidata_id: ?string,
     *         facebook_id: ?string,
     *         instagram_id: ?string,
     *         twitter_id: ?string,
     *     },
     *     keywords: ?array{
     *         id: ?int,
     *         results: ?array<
     *             int<0, max>,
     *             ?array{
     *                 id: ?int,
     *                 name: ?string,
     *             },
     *         >,
     *     },
     *     recommendations: ?array{
     *         page: ?int,
     *         results: ?array{
     *             adult: ?boolean,
     *             backdrop_path: ?string,
     *             id: ?int,
     *             name: ?string,
     *             original_language: ?string,
     *             original_name: ?string,
     *             overview: ?string,
     *             poster_path: ?string,
     *             media_type: ?string,
     *             genre_ids: ?array<int>,
     *             popularity: ?float,
     *             first_air_date: ?string,
     *             vote_average: ?float,
     *             vote_count: ?int,
     *             origin_country: ?array<string>,
     *         },
     *         total_pages: ?int,
     *         total_results: ?int,
     *     },
     *     alternative_titles: ?array{
     *         id: ?int,
     *         results: ?array<
     *             int<0, max>,
     *             array{
     *                 iso_3166_1: ?string,
     *                 title: ?string,
     *                 type: ?string,
     *             },
     *         >,
     *     }
     * }
     */
    public array $data;

    public TMDB $tmdb;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws JsonException
     */
    public function __construct(int $id)
    {
        $this->data = Http::acceptJson()
            ->withUrlParameters(['id' => $id])
            ->get('https://api.TheMovieDB.org/3/tv/{id}', [
                'api_key'            => config('api-keys.tmdb'),
                'language'           => config('app.meta_locale'),
                'append_to_response' => 'videos,images,aggregate_credits,external_ids,keywords,recommendations,alternative_titles',
            ])
            ->json();

        $this->tmdb = new TMDB();
    }

    /**
     * @return ?array{
     *     backdrop: ?string,
     *     episode_run_time: ?array<int>,
     *     first_air_date: ?string,
     *     homepage: ?string,
     *     imdb_id: string,
     *     tvdb_id: string,
     *     in_production: ?bool,
     *     last_air_date: ?string,
     *     name: string,
     *     name_sort: string,
     *     number_of_episodes: ?int,
     *     number_of_seasons: ?int,
     *     origin_country: ?array<string>,
     *     original_language: ?string,
     *     original_name: ?string,
     *     overview: ?string,
     *     popularity: ?float,
     *     poster: ?string,
     *     status: ?string,
     *     vote_average: ?float,
     *     vote_count: ?int,
     * }
     */
    public function getTv(): ?array
    {
        if (isset($this->data['id'])) {
            return [
                'backdrop'           => $this->tmdb->image('backdrop', $this->data),
                'episode_run_time'   => $this->tmdb->ifHasItems('episode_run_time', $this->data),
                'first_air_date'     => $this->tmdb->ifExists('first_air_date', $this->data),
                'homepage'           => $this->data['homepage'],
                'imdb_id'            => substr($this->data['external_ids']['imdb_id'] ?? '', 2),
                'tvdb_id'            => $this->data['external_ids']['tvdb_id'] ?? '',
                'in_production'      => $this->data['in_production'],
                'last_air_date'      => $this->data['last_air_date'],
                'name'               => Str::limit($this->data['name'], 200),
                'name_sort'          => addslashes(str_replace(['The ', 'An ', 'A ', '"'], [''], Str::limit($this->data['name'], 100))),
                'number_of_episodes' => $this->data['number_of_episodes'],
                'number_of_seasons'  => $this->data['number_of_seasons'],
                'origin_country'     => $this->tmdb->ifHasItems('origin_country', $this->data),
                'original_language'  => $this->data['original_language'],
                'original_name'      => $this->data['original_name'],
                'overview'           => $this->data['overview'],
                'popularity'         => $this->data['popularity'],
                'poster'             => $this->tmdb->image('poster', $this->data),
                'status'             => $this->data['status'],
                'vote_average'       => $this->data['vote_average'],
                'vote_count'         => $this->data['vote_count'],
            ];
        }

        return null;
    }

    /**
     * @return array<int, array{
     *     id: int,
     *     name: string,
     * }>
     */
    public function getGenres(): array
    {
        $genres = [];

        foreach ($this->data['genres'] as $genre) {
            $genres[] = [
                'id'   => $genre['id'],
                'name' => $genre['name'],
            ];
        }

        return $genres;
    }

    /**
     * @return array<
     *     int<0, max>,
     *     array{
     *         tv_id: int,
     *         person_id: int,
     *         occupation_id: int,
     *         character: ?string,
     *         order: ?int,
     *     },
     * >
     */
    public function getCredits(): array
    {
        $credits = [];

        foreach ($this->data['aggregate_credits']['cast'] ?? [] as $person) {
            foreach ($person['roles'] as $role) {
                $credits[] = [
                    'tv_id'         => $this->data['id'],
                    'person_id'     => $person['id'],
                    'occupation_id' => Occupations::ACTOR->value,
                    'character'     => $role['character'] ?? '',
                    'order'         => $person['order'] ?? null
                ];
            }
        }

        foreach ($this->data['aggregate_credits']['crew'] ?? [] as $person) {
            foreach ($person['jobs'] as $job) {
                $occupation = Occupations::from_tmdb_job($job['job']);

                if ($occupation !== null) {
                    $credits[] = [
                        'tv_id'         => $this->data['id'],
                        'person_id'     => $person['id'],
                        'occupation_id' => $occupation->value,
                        'character'     => null,
                        'order'         => null,
                    ];
                }
            }
        }

        foreach ($this->data['created_by'] ?? [] as $person) {
            $credits[] = [
                'tv_id'         => $this->data['id'],
                'person_id'     => $person['id'],
                'occupation_id' => Occupations::CREATOR->value,
                'character'     => null,
                'order'         => null,
            ];
        }

        return $credits;
    }

    /**
     * @return array<
     *     int<0, max>,
     *     array{
     *         id: int,
     *         season_number: int,
     *     },
     * >
     */
    public function getSeasons(): array
    {
        $seasons = [];

        foreach ($this->data['seasons'] as $season) {
            if ($season['season_number'] !== null) {
                $seasons[] = [
                    'id'            => $season['id'],
                    'season_number' => $season['season_number'],
                ];
            }
        }

        return $seasons;
    }

    /**
     * @return array<
     *     int<0, max>,
     *     array{
     *         recommendation_tv_id: ?int,
     *         tv_id: ?int,
     *         title: ?string,
     *         vote_average: ?float,
     *         poster: ?string,
     *         first_air_date: ?string,
     *     }
     * >
     */
    public function getRecommendations(): array
    {
        $tv_ids = \App\Models\Tv::query()
            ->select('id')
            ->whereIntegerInRaw('id', array_column($this->data['recommendations']['results'] ?? [], 'id'))
            ->pluck('id');

        $recommendations = [];

        foreach ($this->data['recommendations']['results'] ?? [] as $recommendation) {
            if ($tv_ids->contains($recommendation['id'])) {
                $recommendations[] = [
                    'recommendation_tv_id' => $recommendation['id'],
                    'tv_id'                => $this->data['id'],
                    'title'                => $recommendation['name'],
                    'vote_average'         => $recommendation['vote_average'],
                    'poster'               => $this->tmdb->image('poster', $recommendation),
                    'first_air_date'       => $recommendation['first_air_date'],
                ];
            }
        }

        return $recommendations;
    }

    public function get_trailer(): ?string
    {
        if (!empty($this->data['videos']['results'])) {
            return 'https://www.youtube.com/embed/'.$this->data['videos']['results'][0]['key'];
        }

        return null;
    }
}
