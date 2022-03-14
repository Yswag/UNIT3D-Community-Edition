<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\InviteController
 */
class InviteControllerTest extends TestCase
{
    /** @test */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $invites = \App\Models\Invite::factory()->times(3)->create();

        $response = $this->get(route('staff.invites.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.invite.index');
        $response->assertViewHas('invites', $invites);
        $response->assertViewHas('invitecount');

        // TODO: perform additional assertions
    }

    // test cases...
}