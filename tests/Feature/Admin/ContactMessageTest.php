<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    public function test_guest_cannot_access_messages_management(): void
    {
        $response = $this->get(route('admin.messages.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_messages_index(): void
    {
        ContactMessage::create([
            'name' => 'Michael Scott',
            'email' => 'michael@dundermifflin.com',
            'company' => 'Dunder Mifflin',
            'subject' => 'Bulk Export Inquiry (FCL/LCL)',
            'message' => 'Need 50 bags of civet coffee for our Scranton branch.',
            'is_read' => false,
        ]);

        // Test admin access
        $response = $this->actingAs($this->admin)->get(route('admin.messages.index'));
        $response->assertStatus(200);
        $response->assertSee('Michael Scott');
        $response->assertSee('michael@dundermifflin.com');
        $response->assertSee('Unread');

        // Test editor access
        $responseEditor = $this->actingAs($this->editor)->get(route('admin.messages.index'));
        $responseEditor->assertStatus(200);
        $responseEditor->assertSee('Michael Scott');
    }

    public function test_can_search_and_filter_messages(): void
    {
        $unreadMsg = ContactMessage::create([
            'name' => 'Alice Green',
            'email' => 'alice@specialty.org',
            'company' => 'Green Coffee Co.',
            'subject' => 'Sample Inquiry',
            'message' => 'Interested in micro-lot samples.',
            'is_read' => false,
        ]);

        $readMsg = ContactMessage::create([
            'name' => 'Bob Brown',
            'email' => 'bob@roasters.com',
            'company' => 'Brown Roasters',
            'subject' => 'Partnership',
            'message' => 'Enzymatic partnership question.',
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Search query
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.messages.index', ['search' => 'Alice']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Alice Green');
        $searchResponse->assertDontSee('Bob Brown');

        // Filter unread
        $unreadResponse = $this->actingAs($this->admin)->get(route('admin.messages.index', ['status' => 'unread']));
        $unreadResponse->assertStatus(200);
        $unreadResponse->assertSee('Alice Green');
        $unreadResponse->assertDontSee('Bob Brown');

        // Filter read
        $readResponse = $this->actingAs($this->admin)->get(route('admin.messages.index', ['status' => 'read']));
        $readResponse->assertStatus(200);
        $readResponse->assertSee('Bob Brown');
        $readResponse->assertDontSee('Alice Green');
    }

    public function test_viewing_message_marks_it_as_read(): void
    {
        $message = ContactMessage::create([
            'name' => 'Sarah Connor',
            'email' => 'sarah@resistance.com',
            'subject' => 'Coffee for the troops',
            'message' => 'We need high energy coffee.',
            'is_read' => false,
        ]);

        $this->assertFalse($message->is_read);

        $response = $this->actingAs($this->admin)->get(route('admin.messages.show', $message));
        $response->assertStatus(200);
        $response->assertSee('Sarah Connor');
        $response->assertSee('Coffee for the troops');
        $response->assertSee('Reply via Email');

        $message->refresh();
        $this->assertTrue($message->is_read);
        $this->assertNotNull($message->read_at);
    }

    public function test_can_toggle_message_read_status(): void
    {
        $message = ContactMessage::create([
            'name' => 'Dwight Schrute',
            'email' => 'dwight@schrute-farms.com',
            'subject' => 'Beet and Coffee combo',
            'message' => 'Do you buy beet fertilizer?',
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Toggle to unread
        $response = $this->actingAs($this->admin)->post(route('admin.messages.toggle-read', $message));
        $response->assertRedirect();
        $message->refresh();
        $this->assertFalse($message->is_read);

        // Toggle back to read
        $response2 = $this->actingAs($this->admin)->post(route('admin.messages.toggle-read', $message));
        $response2->assertRedirect();
        $message->refresh();
        $this->assertTrue($message->is_read);
    }

    public function test_can_delete_message(): void
    {
        $message = ContactMessage::create([
            'name' => 'Spam Sender',
            'email' => 'spam@bot.com',
            'message' => 'Cheap SEO services.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.messages.destroy', $message));
        $response->assertRedirect(route('admin.messages.index'));

        $this->assertDatabaseMissing('contact_messages', [
            'id' => $message->id,
        ]);
    }
}
