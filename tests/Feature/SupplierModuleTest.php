<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SupplierModuleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_supplier_details_page_shows_download_report_button(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'name' => 'Acme Supplier',
            'phone' => '01800000000',
            'email' => 'supplier@example.com',
            'total_due' => 90,
        ]);

        Purchase::create([
            'supplier_id' => $supplier->id,
            'date' => '2026-05-24',
            'voucher_no' => 'VCH-1001',
            'total_amount' => 150,
            'paid_amount' => 60,
            'due_amount' => 90,
            'details' => 'Initial purchase',
        ]);

        $response = $this->actingAs($user)->get(route('suppliers.show', $supplier));

        $response->assertOk();
        $response->assertSee('Download Report');
        $response->assertSee('Add Purchase');
        $response->assertSee('Record Payment');
    }

    public function test_supplier_report_can_be_downloaded_as_pdf(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'name' => 'Report Supplier',
            'phone' => '01600000000',
            'email' => 'report-supplier@example.com',
            'address' => 'Dhaka',
            'total_due' => 140,
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $supplier->id,
            'date' => '2026-05-24',
            'voucher_no' => 'VCH-5001',
            'total_amount' => 200,
            'paid_amount' => 60,
            'due_amount' => 140,
            'details' => 'Report purchase',
        ]);

        PurchasePayment::create([
            'supplier_id' => $supplier->id,
            'purchase_id' => $purchase->id,
            'date' => '2026-05-24',
            'amount' => 60,
            'details' => 'Report payment',
        ]);

        $response = $this->actingAs($user)->get(route('suppliers.report.pdf', $supplier));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader(
            'content-disposition',
            'attachment; filename=supplier_report_'.$supplier->id.'_report-supplier.pdf'
        );
    }
}
