<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class MonthlyReportTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_download_monthly_report(): void
    {
        $response = $this->get(route('reports.monthly.pdf', ['month' => '2026-05']));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_download_monthly_report_as_pdf(): void
    {
        $user = User::factory()->create();

        // Create a customer & supplier for relationships
        $customer = Customer::create([
            'name' => 'John Doe',
            'total_due' => 0,
        ]);

        // Create some sales, purchases, and expenses in May 2026
        Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-10',
            'invoice_no' => 'INV-101',
            'category' => 'Daily Sale',
            'total_amount' => 1500.00,
            'paid_amount' => 1000.00,
            'due_amount' => 500.00,
        ]);

        // Create some expenses in May 2026
        Expense::create([
            'title' => 'Office Rent',
            'amount' => 400.00,
            'cost_type' => 'Rent',
            'datetime' => '2026-05-01 12:00:00',
        ]);

        $response = $this->actingAs($user)->get(route('reports.monthly.pdf', ['month' => '2026-05']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader(
            'content-disposition',
            'attachment; filename=monthly_report_2026_05.pdf'
        );
    }

    public function test_monthly_report_defaults_to_current_month(): void
    {
        $user = User::factory()->create();
        $currentMonth = Carbon::today()->format('Y_m');

        $response = $this->actingAs($user)->get(route('reports.monthly.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader(
            'content-disposition',
            'attachment; filename=monthly_report_'.$currentMonth.'.pdf'
        );
    }
}
