<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Livewire;

use App\Models\Note;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UserNotes extends Component
{
    use WithPagination;

    public User $user;

    public string $message = '';

    public int $perPage = 25;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $rules = [
        'message' => [
            'required',
            'filled',
        ],
    ];

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Note>
     */
    #[Computed]
    final public function notes(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Note::query()
            ->with('staffuser', 'staffuser.group')
            ->where('user_id', '=', $this->user->id)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-notes', [
            'notes' => $this->notes,
        ]);
    }

    final public function store(): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $this->validate();

        Note::create([
            'user_id'  => $this->user->id,
            'staff_id' => auth()->id(),
            'message'  => $this->message,
        ]);

        $this->message = '';

        $this->dispatch('success', type: 'success', message: 'Note has successfully been posted!');
    }

    final public function destroy(int $id): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        Note::findOrFail($id)->delete();

        $this->dispatch('success', type: 'success', message: 'Note has successfully been deleted!');
    }
}
