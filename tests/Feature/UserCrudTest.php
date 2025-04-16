<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_crud_api()
    {
        Storage::fake('public');

        // Test for [Create]
        $testData = [
            'name' => 'Ilija',
            'surname' => 'Milanov',
            'gender' => 'male',
            'email' => 'jdkls7725@gmail.com',
            'phone' => '+44 1244 94 1793',
            'country' => 'UK',
            'introduction' => 'I am a full stack web developer.',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'introduction' => 'Just a test user.',
            'selfie' => UploadedFile::fake()->image('selfie.jpg'),
        ];
        $response = $this->postJson('/api/users', $testData);
        
        // Assert: Check if created
        $response->assertCreated();
        $this->assertDatabaseHas('users', ['email' => 'jdkls7725@gmail.com']);

        // Assert the selfie file was stored
        $createdUser = $response->json();
        Storage::disk('public')->assertExists( $createdUser['selfie']);

        // Test for [List]
        $response = $this->getJson('/api/users');
        // Assert: Check status and data count
        $response->assertStatus(200)
                ->assertJsonCount(1);
        $users = $response->json();
        $user = (object) $users[0];

        // Test for [Get]
        $response = $this->getJson("/api/users/{$user->id}");
        // Assert: Check response data
        $response->assertStatus(200)
             ->assertJsonFragment($createdUser); // Check if newly created user is fetched.
        
        // Test for [Update]
        $data = [
            'name' => 'Ilija Updated'
        ];
        $response = $this->putJson("/api/users/{$user->id}", $data);
        // Assert: check response and DB
        $response->assertStatus(200)
                ->assertJsonFragment($data);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Ilija Updated',
        ]);

        // Test for [Update]
        $response = $this->deleteJson("/api/users/{$user->id}");
        // Assert: Check response status and DB deletion
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        // Check if selfie is removed
        Storage::disk('public')->assertMissing( $createdUser['selfie']);
    }

    public function test_email_validation_api()
    {
        Storage::fake('public');

        // Test for [Create]
        $testData = [
            'name' => 'Ilija',
            'surname' => 'Milanov',
            'gender' => 'male',
            'email' => 'jdkls7725',
            'phone' => '+44 1244 94 1793',
            'country' => 'UK',
            'introduction' => 'I am a full stack web developer.',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'introduction' => 'Just a test user.',
        ];
        $response = $this->postJson('/api/users', $testData);
        
        // Assert: Check if created
        $response->assertStatus(422)
            ->assertJsonFragment(["message" => "The email field must be a valid email address."]);
    }

    public function test_create_a_user()
    {
        Storage::fake('public');

        // Test for [Create]
        $testData = [
            'name' => 'Ilija',
            'surname' => 'Milanov',
            'gender' => 'male',
            'email' => 'jdkls7725@gmail.com',
            'phone' => '+44 1244 94 1793',
            'country' => 'UK',
            'introduction' => 'I am a full stack web developer.',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'introduction' => 'Just a test user.',
            'selfie' => UploadedFile::fake()->image('selfie.jpg'),
        ];
        $response = $this->post(route('users.store'), $testData);
        
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'jdkls7725@gmail.com']);
        Storage::disk('public')->assertExists('selfies/' . $testData['selfie']->hashName());
    }
    
    
    public function test_get_user_detail()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        // Then assert file exists
        Storage::disk('public')->assertExists($user->selfie);

        $response = $this->get(route('users.show', $user->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->selfie);

        // Let's remove to delete file
        $this->delete(route('users.destroy', $user->id));
    }

    
    public function test_update_a_user()
    {
        $user = User::factory()->create();

        $update = [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '987654321',
            'country' => 'CA',
            'gender' => 'female',
            'introduction' => 'Updated user.',
        ];

        $response = $this->put(route('users.update', $user->id), $update);

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'jane@example.com',
            'name' => 'Jane',
        ]);

        // Let's remove to delete file
        $this->delete(route('users.destroy', $user->id));
    }

    
    public function test_delete_a_user()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        // Check if selfie is removed
        Storage::disk('public')->assertMissing( $user['selfie']);
    }
}
