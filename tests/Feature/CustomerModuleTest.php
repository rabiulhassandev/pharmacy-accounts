<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CustomerModuleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_customer_details_page_shows_sales_and_payments_lists(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Acme Clinic',
            'phone' => '01700000000',
            'email' => 'acme@example.com',
            'total_due' => 150,
        ]);

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-24',
            'invoice_no' => 'INV-1001',
            'total_amount' => 200,
            'paid_amount' => 50,
            'due_amount' => 150,
            'details' => 'Initial sale',
        ]);

        SalePayment::create([
            'customer_id' => $customer->id,
            'sale_id' => $sale->id,
            'date' => '2026-05-24',
            'amount' => 50,
            'details' => 'Cash received',
        ]);

        $response = $this->actingAs($user)->get(route('customers.show', $customer));

        $response->assertOk();
        $response->assertSee('Download Report');
        $response->assertSee('Add Sale');
        $response->assertSee('Record Payment');
        $response->assertSee('Sales History');
        $response->assertSee('Received Payments');
        $response->assertSee('INV-1001');
        $response->assertSee('Cash received');
    }

    public function test_customer_report_can_be_downloaded_as_pdf(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Report Customer',
            'phone' => '01900000000',
            'email' => 'report@example.com',
            'address' => 'Dhaka',
            'total_due' => 125,
        ]);

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-24',
            'invoice_no' => 'INV-5001',
            'total_amount' => 200,
            'paid_amount' => 75,
            'due_amount' => 125,
            'details' => 'Report sale',
        ]);

        SalePayment::create([
            'customer_id' => $customer->id,
            'sale_id' => $sale->id,
            'date' => '2026-05-24',
            'amount' => 75,
            'details' => 'Report payment',
        ]);

        $response = $this->actingAs($user)->get(route('customers.report.pdf', $customer));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader(
            'content-disposition',
            'attachment; filename=customer_report_'.$customer->id.'_report-customer.pdf'
        );
    }

    public function test_store_sale_updates_customer_due_balance(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Beta Pharmacy',
            'total_due' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('customers.sales.store', $customer), [
            'date' => '2026-05-24',
            'invoice_no' => 'INV-2001',
            'total_amount' => 300,
            'paid_amount' => 100,
            'details' => 'Retail sale',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $customer->refresh();
        $sale = $customer->sales()->first();

        $this->assertNotNull($sale);
        $this->assertSame('INV-2001', $sale->invoice_no);
        $this->assertEquals(200, $sale->due_amount);
        $this->assertEquals(200, $customer->total_due);
    }

    public function test_store_payment_updates_customer_and_sale_due_balances(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Gamma Medical',
            'total_due' => 250,
        ]);

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-24',
            'invoice_no' => 'INV-3001',
            'total_amount' => 250,
            'paid_amount' => 0,
            'due_amount' => 250,
        ]);

        $response = $this->actingAs($user)->post(route('customers.payments.store', $customer), [
            'sale_id' => $sale->id,
            'date' => '2026-05-24',
            'amount' => 100,
            'details' => 'Bank transfer',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $customer->refresh();
        $sale->refresh();

        $this->assertEquals(150, $customer->total_due);
        $this->assertEquals(100, $sale->paid_amount);
        $this->assertEquals(150, $sale->due_amount);
        $this->assertDatabaseHas('sale_payments', [
            'customer_id' => $customer->id,
            'sale_id' => $sale->id,
            'amount' => '100.00',
        ]);
    }

    public function test_customer_with_transaction_history_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Delta Pharmacy',
            'total_due' => 0,
        ]);

        Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-24',
            'total_amount' => 120,
            'paid_amount' => 120,
            'due_amount' => 0,
        ]);

        $response = $this->from(route('customers.show', $customer))
            ->actingAs($user)
            ->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.show', $customer));
        $response->assertSessionHasErrors();
        $this->assertSame(
            'Cannot delete a customer that has associated sale or payment history.',
            session('errors')->first()
        );
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_payment_cannot_exceed_selected_sale_due_amount(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Epsilon Care',
            'total_due' => 100,
        ]);

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'date' => '2026-05-24',
            'invoice_no' => 'INV-4001',
            'total_amount' => 100,
            'paid_amount' => 0,
            'due_amount' => 100,
        ]);

        $response = $this->from(route('customers.show', $customer))
            ->actingAs($user)
            ->post(route('customers.payments.store', $customer), [
                'sale_id' => $sale->id,
                'date' => '2026-05-24',
                'amount' => 150,
                'details' => 'Overpayment attempt',
            ]);

        $response->assertRedirect(route('customers.show', $customer));
        $response->assertSessionHasErrors();
        $this->assertSame(
            'Received amount cannot exceed the customer due balance.',
            session('errors')->first()
        );

        $customer->refresh();
        $sale->refresh();

        $this->assertEquals(100, $customer->total_due);
        $this->assertEquals(100, $sale->due_amount);
        $this->assertDatabaseCount('sale_payments', 0);
    }
}
