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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePoll;
use App\Models\Option;
use App\Models\Poll;
use App\Repositories\ChatRepository;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\PollControllerTest
 */
class PollController extends Controller
{
    /**
     * PollController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display All Polls.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.index', [
            'polls' => Poll::latest()->paginate(25),
        ]);
    }

    /**
     * Show A Poll.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.show', [
            'poll' => Poll::findOrFail($id),
        ]);
    }

    /**
     * Poll Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.create');
    }

    /**
     * Store A New Poll.
     */
    public function store(StorePoll $storePoll): \Illuminate\Http\RedirectResponse
    {
        $user = $storePoll->user();

        $poll = $storePoll->user() ? $user->polls()->create($storePoll->all()) : Poll::create($storePoll->all());

        $options = collect($storePoll->input('options'))->map(fn ($value) => new Option(['name' => $value]));
        $poll->options()->saveMany($options);

        $pollUrl = href_poll($poll);

        $this->chatRepository->systemMessage(
            sprintf('A new poll has been created [url=%s]%s[/url] vote on it now! :slight_smile:', $pollUrl, $poll->title)
        );

        return to_route('staff.polls.index')
            ->withSuccess('Your poll has been created.');
    }

    /**
     * Poll Edit Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.poll.edit', [
            'poll' => Poll::findOrFail($id),
        ]);
    }

    /**
     * Update A New Poll.
     *
     * @throws Exception
     */
    public function update(StorePoll $storePoll, int $id): \Illuminate\Http\RedirectResponse
    {
        $poll = Poll::findOrFail($id);

        $poll->title = $storePoll->input('title');

        $poll->multiple_choice = (bool) $storePoll->input('multiple_choice');

        // Remove the deleted options in poll
        $oldOptionIds = collect($poll->options)->map(fn ($option) => $option->id)->all();

        $existingOldOptionIds = collect($storePoll->input('option-id'))->map(fn ($id) => (int) $id)->all();

        foreach (array_diff($oldOptionIds, $existingOldOptionIds) as $id) {
            $option = Option::findOrFail($id);
            $option->delete();
        }

        // Update existing options
        $existingOldOptionContents = collect($storePoll->input('option-content'))->map(fn ($content) => (string) $content)->all();

        if (\count($existingOldOptionContents) === \count($existingOldOptionIds)) {
            $len = \count($existingOldOptionContents);

            for ($i = 0; $i < $len; $i++) {
                $option = Option::findOrFail($existingOldOptionIds[$i]);
                $option->name = $existingOldOptionContents[$i];
                $option->save();
            }
        }

        // Insert new options
        $newOptions = collect($storePoll->input('new-option-content'))->map(fn ($content) => new Option(['name' => $content]));

        $poll->options()->saveMany($newOptions);

        // Last work from store()
        $pollUrl = href_poll($poll);

        $this->chatRepository->systemMessage(
            sprintf('A poll has been updated [url=%s]%s[/url] vote on it now! :slight_smile:', $pollUrl, $poll->title)
        );

        $poll->save();

        return to_route('staff.polls.index')
            ->withSuccess('Your poll has been edited.');
    }

    /**
     * Delete A Poll.
     *
     * @throws Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        Poll::findOrFail($id)->delete();
        Option::where('poll_id', '=', $id)->delete();

        return to_route('staff.polls.index')
            ->withSuccess('Poll has successfully been deleted');
    }
}
