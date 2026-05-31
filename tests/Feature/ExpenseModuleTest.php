<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExpenseModuleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_access_expenses(): void
    {
        $response = $this->get(route('expenses.index'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('expenses.store'), []);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_expenses_index(): void
    {
        $user = User::factory()->create();
        $expense = Expense::create([
            'title' => 'Office Rent',
            'amount' => 5000.00,
            'cost_type' => 'Rent',
            'datetime' => '2026-05-28 10:00:00',
            'note' => 'Rent for May 2026',
        ]);

        $response = $this->actingAs($user)->get(route('expenses.index'));

        $response->assertOk();
        $response->assertSee('Expenses Management');
        $response->assertSee('Add New Expense');
        $response->assertSee('Office Rent');
        $response->assertSee('Rent');
        $response->assertSee('5,000.00');
    }

    public function test_authenticated_user_can_store_expense(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'title' => 'Electricity Bill',
            'amount' => 1200.50,
            'cost_type' => 'Bill',
            'datetime' => '2026-05-28 12:00:00',
            'note' => 'May utilities',
        ]);

        $response->assertRedirect(route('expenses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'title' => 'Electricity Bill',
            'amount' => '1200.50',
            'cost_type' => 'Bill',
            'note' => 'May utilities',
        ]);
    }

    public function test_store_expense_validation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();

        // 1. Missing required fields
        $response = $this->actingAs($user)->post(route('expenses.store'), []);
        $response->assertSessionHasErrors(['title', 'amount', 'cost_type', 'datetime']);

        // 2. Invalid cost type and negative amount
        $response = $this->actingAs($user)->post(route('expenses.store'), [
            'title' => 'Snacks',
            'amount' => -10,
            'cost_type' => 'InvalidType',
            'datetime' => '2026-05-28 12:00:00',
        ]);
        $response->assertSessionHasErrors(['amount', 'cost_type']);
    }

    public function test_authenticated_user_can_delete_expense(): void
    {
        $user = User::factory()->create();
        $expense = Expense::create([
            'title' => 'Tea Cost',
            'amount' => 150.00,
            'cost_type' => 'Daily Staff Cost',
            'datetime' => '2026-05-28 09:30:00',
        ]);

        $response = $this->actingAs($user)->delete(route('expenses.destroy', $expense));

        $response->assertRedirect(route('expenses.index'));
        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }
}
