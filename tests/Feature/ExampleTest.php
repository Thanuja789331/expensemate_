<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    // Runs before each test
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role'      => 'user',
            'is_active' => true,
        ]);

        $this->category = Category::factory()->create([
            'status' => 'active'
        ]);
    }

    // TC01: Unauthenticated user redirected to login
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    // TC02: Authenticated user can see dashboard
    public function test_authenticated_user_can_see_dashboard(): void
    {
        $response = $this->actingAs($this->user)
                         ->get('/dashboard');
        $response->assertStatus(200);
    }

    // TC03: User can create expense with valid data
    public function test_user_can_create_expense(): void
    {
        $response = $this->actingAs($this->user)
                         ->post('/expenses', [
                             'type'         => 'expense',
                             'category_id'  => $this->category->id,
                             'amount'       => 150.00,
                             'expense_date' => now()->format('Y-m-d'),
                             'note'         => 'Test expense',
                         ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $this->user->id,
            'amount'  => 150.00,
            'type'    => 'expense',
        ]);
    }

    // TC04: Validation fails with empty amount
    public function test_expense_requires_valid_amount(): void
    {
        $response = $this->actingAs($this->user)
                         ->post('/expenses', [
                             'type'         => 'expense',
                             'category_id'  => $this->category->id,
                             'amount'       => 0,
                             'expense_date' => now()->format('Y-m-d'),
                         ]);

        $response->assertSessionHasErrors(['amount']);
    }

    // TC05: User cannot access another user's expense
    public function test_user_cannot_edit_another_users_expense(): void
    {
        $otherUser = User::factory()->create();
        $expense   = Expense::factory()->create([
            'user_id'     => $otherUser->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->get("/expenses/{$expense->id}/edit");

        $response->assertStatus(403);
    }

    // TC06: Normal user cannot access admin dashboard
    public function test_normal_user_cannot_access_admin(): void
    {
        $response = $this->actingAs($this->user)
                         ->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    // TC07: Admin can access admin dashboard
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
                         ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    // TC08: API login returns token
    public function test_api_login_returns_token(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email'       => $this->user->email,
            'password'    => 'password',
            'device_name' => 'TestDevice',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'token_type', 'user']);
    }

    // TC09: API requires token for protected routes
    public function test_api_requires_token(): void
    {
        $response = $this->getJson('/api/expenses');
        $response->assertStatus(401);
    }

    // TC10: Authenticated API returns expenses
    public function test_authenticated_api_returns_expenses(): void
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/expenses');

        $response->assertStatus(200);
    }
}
