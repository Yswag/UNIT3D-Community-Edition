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

use App\Livewire\NoteSearch;
use App\Models\Group;
use App\Models\Note;
use App\Models\User;

beforeEach(function (): void {
    $this->staffUser = User::factory()->create([
        'group_id' => fn () => Group::factory()->create([
            'is_owner' => true,
            'is_admin' => true,
            'is_modo'  => true,
        ])->id,
    ]);
});

test('destroy returns an ok response', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $note = Note::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('staff.notes.destroy', [$note]));

    $response->assertOk();
    $this->assertModelMissing($note);

    // TODO: perform additional assertions
});

test('index returns an ok response', function (): void {
    $response = $this->actingAs($this->staffUser)->get(route('staff.notes.index'));
    $response->assertOk();
    $response->assertViewIs('Staff.note.index');
    $response->assertSeeLivewire(NoteSearch::class);
});
