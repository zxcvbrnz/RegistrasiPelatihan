<?php

namespace Tests\Feature;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesertaTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_page_is_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Nama lengkap')
            ->assertSee('Tempat lahir')
            ->assertSee('Tanggal lahir');
    }

    public function test_valid_participant_data_is_saved_and_shown_in_the_list(): void
    {
        $response = $this->post('/peserta', [
            'nama' => 'Aditya Pratama',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2000-05-15',
        ]);

        $response->assertRedirect(route('peserta.create'));
        $this->get('/')
            ->assertOk()
            ->assertSee('data-sweetalert-success')
            ->assertSee('Data kamu telah dikirim.');
        $this->assertDatabaseHas('pesertas', [
            'nama' => 'Aditya Pratama',
            'tempat_lahir' => 'Bandung',
        ]);
        $this->assertSame('2000-05-15', Peserta::firstOrFail()->tanggal_lahir->format('Y-m-d'));

        $this->get('/data-peserta')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get('/data-peserta')
            ->assertOk()
            ->assertSee('Aditya Pratama')
            ->assertSee('Bandung')
            ->assertSee('15/05/2000');
    }

    public function test_participant_data_page_requires_an_admin_login(): void
    {
        $this->get('/data-peserta')->assertRedirect(route('login'));
        $this->get('/admin/login')->assertOk()->assertSee('Login admin');
    }

    public function test_admin_can_log_in_and_log_out(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secure-password',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secure-password',
        ])->assertRedirect(route('peserta.index'));

        $this->assertAuthenticatedAs($admin);

        $this->post('/admin/logout')->assertRedirect(route('peserta.create'));
        $this->assertGuest();
    }

    public function test_invalid_admin_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secure-password',
        ]);

        $this->from('/admin/login')
            ->post('/admin/login', [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_invalid_participant_data_is_not_saved(): void
    {
        $this->from('/')
            ->post('/peserta', [
                'nama' => '',
                'tempat_lahir' => '',
                'tanggal_lahir' => now()->addDay()->toDateString(),
            ])
            ->assertRedirect('/')
            ->assertSessionHasErrors(['nama', 'tempat_lahir', 'tanggal_lahir']);

        $this->assertDatabaseCount('pesertas', 0);
    }

    public function test_honeypot_submission_is_rejected_without_saving_data(): void
    {
        $this->from('/')
            ->post('/peserta', [
                'nama' => 'Bot',
                'tempat_lahir' => 'Internet',
                'tanggal_lahir' => '2000-05-15',
                'website' => 'https://spam.example',
            ])
            ->assertRedirect('/')
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('pesertas', 0);
    }

    public function test_participant_submissions_are_limited_to_five_per_minute(): void
    {
        $data = [
            'nama' => 'Peserta',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2000-05-15',
        ];

        for ($submission = 0; $submission < 5; $submission++) {
            $this->post('/peserta', $data)->assertRedirect(route('peserta.create'));
        }

        $this->post('/peserta', $data)->assertTooManyRequests();

        $this->assertDatabaseCount('pesertas', 5);
    }

    public function test_list_page_shows_an_empty_state_when_there_are_no_participants(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/data-peserta')
            ->assertOk()
            ->assertSee('Belum ada data peserta');
    }

    public function test_admin_can_delete_a_participant_without_an_edit_option(): void
    {
        $peserta = Peserta::create([
            'nama' => 'Aditya Pratama',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2000-05-15',
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/data-peserta')
            ->assertOk()
            ->assertSee('Hapus')
            ->assertDontSee('Edit');

        $this->delete(route('peserta.destroy', $peserta))
            ->assertRedirect(route('peserta.index'));

        $this->assertDatabaseMissing('pesertas', ['id' => $peserta->id]);
    }

    public function test_participant_cannot_be_deleted_without_admin_login(): void
    {
        $peserta = Peserta::create([
            'nama' => 'Aditya Pratama',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2000-05-15',
        ]);

        $this->delete(route('peserta.destroy', $peserta))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('pesertas', ['id' => $peserta->id]);
    }
}
