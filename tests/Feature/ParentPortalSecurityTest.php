<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ParentPortalSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_dashboard_does_not_expose_parent_code(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'nis' => 'NIS-10001',
            'phone' => null,
            'class_id' => null,
            'parent_code' => 'ORTU-ABCDEF1234',
        ]);

        $response = $this->actingAs($user)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertDontSee($student->parent_code, false);
    }

    public function test_parent_access_route_is_rate_limited(): void
    {
        $ip = '127.0.0.1';
        $code = 'ORTU-FAKE12345';
        $codeHash = hash('sha256', strtoupper($code));

        RateLimiter::clear('parent:access:ip:' . $ip);
        RateLimiter::clear('parent:access:code:' . $codeHash);

        for ($i = 0; $i < 5; $i++) {
            $response = $this
                ->withServerVariables(['REMOTE_ADDR' => $ip])
                ->post(route('parent.access'), [
                    'parent_code' => $code,
                ]);

            $response->assertStatus(302);
        }

        $blocked = $this
            ->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post(route('parent.access'), [
                'parent_code' => $code,
            ]);

        $blocked->assertStatus(429);
    }
}
