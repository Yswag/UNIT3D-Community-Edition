<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\UserController
 */
class UserControllerTest extends TestCase
{
    /** @test */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_edit', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /** @test */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('user_search'));

        $response->assertOk();
        $response->assertViewIs('Staff.user.index');

        // TODO: perform additional assertions
    }

    /** @test */
    public function settings_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $roles = \App\Models\Role::factory()->times(3)->create();
        $internals = \App\Models\Internal::factory()->times(3)->create();
        $notes = \App\Models\Note::factory()->times(3)->create();

        $response = $this->get(route('user_setting', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('Staff.user.edit');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('roles', $roles);
        $response->assertViewHas('internals', $internals);
        $response->assertViewHas('notes', $notes);

        // TODO: perform additional assertions
    }

    // test cases...
}