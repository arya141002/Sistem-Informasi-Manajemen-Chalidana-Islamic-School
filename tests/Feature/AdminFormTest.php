<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_administration_staff_requires_a_campus(): void
    {
        $this->actingAs(User::factory()->create());
        $role = Role::create([
            'name' => 'administration_staff',
            'display_name' => 'Administration Staff',
        ]);

        $response = $this->postJson(route('user.store'), [
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => 'password123',
            'role_id' => $role->id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('campus_id');
    }

    public function test_administration_staff_can_be_created_with_a_campus(): void
    {
        $this->actingAs(User::factory()->create());
        $role = Role::create([
            'name' => 'administration_staff',
            'display_name' => 'Administration Staff',
        ]);
        $campus = $this->createCampus();

        $response = $this->postJson(route('user.store'), [
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => 'password123',
            'role_id' => $role->id,
            'campus_id' => $campus->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'email' => 'staff@example.com',
            'campus_id' => $campus->id,
        ]);
    }

    public function test_administration_staff_can_be_assigned_to_multiple_campuses(): void
    {
        $this->actingAs(User::factory()->create());
        $role = Role::create([
            'name' => 'administration_staff',
            'display_name' => 'Administration Staff',
        ]);
        $primary = $this->createCampus('primary-malang', 'CIS Primary Malang');
        $preschool = $this->createCampus('preschool-malang', 'CIS Preschool Malang');

        $response = $this->postJson(route('user.store'), [
            'name' => 'Malang Admin',
            'email' => 'malang@example.com',
            'password' => 'password123',
            'role_id' => $role->id,
            'campus_id' => $primary->id,
            'campus_ids' => [$primary->id, $preschool->id],
        ]);

        $response->assertOk();
        $user = User::where('email', 'malang@example.com')->firstOrFail();
        $this->assertEqualsCanonicalizing(
            [$primary->id, $preschool->id],
            $user->campuses()->pluck('campuses.id')->all()
        );
    }

    public function test_menu_parent_can_have_no_url(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson(route('menu.store'), [
            'title' => 'Content',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('menus', [
            'title' => 'Content',
            'url' => null,
        ]);
    }

    public function test_menu_cannot_be_its_own_parent(): void
    {
        $this->actingAs(User::factory()->create());
        $menu = Menu::create(['title' => 'Content']);

        $response = $this->putJson(route('menu.update', $menu), [
            'title' => 'Content',
            'parent_id' => $menu->id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('parent_id');
    }

    private function createCampus(string $slug = 'main-campus', string $title = 'Main Campus'): Campus
    {
        $areaId = DB::table('campus_area')->insertGetId([
            'title' => 'Main Area',
            'slug' => $slug . '-area',
            'img' => 'area.jpg',
            'pagehead' => 'Main Area',
            'phone' => '0000000000',
            'address' => 'Address',
            'address_id' => 'Alamat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Campus::create([
            'catcampus_id' => $areaId,
            'title' => $title,
            'NPSN' => '',
            'school_tour' => '',
            'subject' => '',
            'slug' => $slug,
            'img_header' => '',
            'img_principal' => '',
            'from' => '',
            'from_id' => '',
            'message' => '',
            'message_id' => '',
            'address' => '',
            'maplocation' => '',
            'content' => '',
            'content_id' => '',
        ]);
    }
}
