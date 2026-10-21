<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval

/**
 * Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
 *
 * For any invoice with approval = 'Lock', the view must show "Minta Unlock" button
 * and NOT show "Edit" directly.
 * For any invoice with approval = 'Unlock', the view must show "Edit" and "Lock" buttons.
 *
 * Validates: Requirements 3.2, 3.3, 3.5, 11.2, 11.3, 11.4, 11.5
 *
 * Since this is a unit test (no HTTP/Blade rendering), the conditional logic from
 * daftar-invoiceTokabe.blade.php is extracted as pure PHP helper functions and tested
 * directly with 100 varied invoice objects.
 */
class PenjualanTokabeViewRenderTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Helper functions — mirrors the Blade conditional logic in
    // resources/views/pages/invoices/tokabe/daftar-invoiceTokabe.blade.php
    // -------------------------------------------------------------------------

    /**
     * Should the "Edit" button be shown?
     * Blade: @if($inv->approval == 'Unlock') ... Edit ...
     */
    private function shouldShowEdit(object $inv): bool
    {
        return $inv->approval === 'Unlock';
    }

    /**
     * Should the "Lock" button be shown?
     * Blade: @if($inv->approval == 'Unlock') ... Lock ...
     */
    private function shouldShowLock(object $inv): bool
    {
        return $inv->approval === 'Unlock';
    }

    /**
     * Should the "Minta Unlock" button be shown?
     * Blade: @else (i.e. approval != 'Unlock') ... Minta Unlock ...
     */
    private function shouldShowMintaUnlock(object $inv): bool
    {
        return $inv->approval !== 'Unlock';
    }

    /**
     * Should the "Request Batal" button be shown for Admin?
     * Blade: @if($isAdminTkb && $inv->status !== 'Batal') ... Request Batal ...
     */
    private function shouldShowRequestBatal(object $inv): bool
    {
        return $inv->status !== 'Batal';
    }

    /**
     * Should the "Request Delete" button be shown?
     * Fitur delete telah dihapus dari blade.
     */
    private function shouldShowRequestDelete(bool $isAdmin): bool
    {
        return false;
    }

    // -------------------------------------------------------------------------
    // Data Providers
    // -------------------------------------------------------------------------

    /**
     * Generate 100 varied invoice objects with random approval states.
     * Covers: Lock, Unlock, null, empty string, and other unexpected values.
     */
    public static function invoiceApprovalProvider(): array
    {
        $cases = [];

        // Possible approval values — weighted toward the two valid states
        $approvalValues = [
            'Lock',   // 0
            'Unlock', // 1
            'Lock',   // 2
            'Unlock', // 3
            'Lock',   // 4
            null,     // 5  — edge case: null treated as non-Unlock → Lock behaviour
            '',       // 6  — edge case: empty string → Lock behaviour
            'lock',   // 7  — edge case: wrong case → Lock behaviour
            'UNLOCK', // 8  — edge case: wrong case → Lock behaviour
            'Lock',   // 9
        ];

        $statuses = ['Belum Lunas', 'Lunas', 'Batal', 'DP'];

        for ($i = 0; $i < 100; $i++) {
            $approval = $approvalValues[$i % count($approvalValues)];

            $inv = (object) [
                'id'               => $i + 1,
                'nomor_invoice'    => 'TKB-' . ($i + 1000),
                'customer'         => 'Customer ' . ($i + 1),
                'perusahaan'       => 'PT. Test ' . ($i + 1),
                'status'           => $statuses[$i % count($statuses)],
                'approval'         => $approval,
                'total_pembayaran' => ($i + 1) * 150000,
                'sisa_pembayaran'  => ($i % 3 === 0) ? 0 : ($i * 50000),
            ];

            $cases["iteration_$i [approval=" . var_export($approval, true) . "]"] = [$inv];
        }

        return $cases;
    }

    /**
     * Generate 50 invoice objects strictly with approval = 'Lock'.
     */
    public static function lockedInvoiceProvider(): array
    {
        $cases = [];
        $statuses = ['Belum Lunas', 'Lunas', 'Batal', 'DP'];

        for ($i = 0; $i < 50; $i++) {
            $inv = (object) [
                'id'            => $i + 1,
                'nomor_invoice' => 'TKB-LOCK-' . ($i + 1),
                'customer'      => 'Customer Lock ' . ($i + 1),
                'status'        => $statuses[$i % count($statuses)],
                'approval'      => 'Lock',
            ];

            $cases["lock_iteration_$i"] = [$inv];
        }

        return $cases;
    }

    /**
     * Generate 50 invoice objects strictly with approval = 'Unlock'.
     */
    public static function unlockedInvoiceProvider(): array
    {
        $cases = [];
        $statuses = ['Belum Lunas', 'Lunas', 'Batal', 'DP'];

        for ($i = 0; $i < 50; $i++) {
            $inv = (object) [
                'id'            => $i + 1,
                'nomor_invoice' => 'TKB-UNLOCK-' . ($i + 1),
                'customer'      => 'Customer Unlock ' . ($i + 1),
                'status'        => $statuses[$i % count($statuses)],
                'approval'      => 'Unlock',
            ];

            $cases["unlock_iteration_$i"] = [$inv];
        }

        return $cases;
    }

    // -------------------------------------------------------------------------
    // Property 8a — Lock state: "Minta Unlock" shown, "Edit" NOT shown
    // Validates: Requirements 3.2, 3.5, 11.2, 11.4
    // -------------------------------------------------------------------------

    /**
     * @dataProvider lockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Lock', shouldShowMintaUnlock must be true.
     */
    public function test_lock_invoice_shows_minta_unlock_button(object $inv): void
    {
        $this->assertSame(
            'Lock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Lock'
        );

        $this->assertTrue(
            $this->shouldShowMintaUnlock($inv),
            "Invoice #{$inv->id} with approval='Lock' must show 'Minta Unlock' button"
        );
    }

    /**
     * @dataProvider lockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Lock', shouldShowEdit must be false.
     */
    public function test_lock_invoice_does_not_show_edit_button(object $inv): void
    {
        $this->assertSame(
            'Lock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Lock'
        );

        $this->assertFalse(
            $this->shouldShowEdit($inv),
            "Invoice #{$inv->id} with approval='Lock' must NOT show 'Edit' button"
        );
    }

    /**
     * @dataProvider lockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Lock', shouldShowLock must be false.
     */
    public function test_lock_invoice_does_not_show_lock_button(object $inv): void
    {
        $this->assertSame(
            'Lock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Lock'
        );

        $this->assertFalse(
            $this->shouldShowLock($inv),
            "Invoice #{$inv->id} with approval='Lock' must NOT show 'Lock' button"
        );
    }

    // -------------------------------------------------------------------------
    // Property 8b — Unlock state: "Edit" and "Lock" shown, "Minta Unlock" NOT shown
    // Validates: Requirements 3.3, 11.3, 11.5
    // -------------------------------------------------------------------------

    /**
     * @dataProvider unlockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Unlock', shouldShowEdit must be true.
     */
    public function test_unlock_invoice_shows_edit_button(object $inv): void
    {
        $this->assertSame(
            'Unlock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Unlock'
        );

        $this->assertTrue(
            $this->shouldShowEdit($inv),
            "Invoice #{$inv->id} with approval='Unlock' must show 'Edit' button"
        );
    }

    /**
     * @dataProvider unlockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Unlock', shouldShowLock must be true.
     */
    public function test_unlock_invoice_shows_lock_button(object $inv): void
    {
        $this->assertSame(
            'Unlock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Unlock'
        );

        $this->assertTrue(
            $this->shouldShowLock($inv),
            "Invoice #{$inv->id} with approval='Unlock' must show 'Lock' button"
        );
    }

    /**
     * @dataProvider unlockedInvoiceProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval = 'Unlock', shouldShowMintaUnlock must be false.
     */
    public function test_unlock_invoice_does_not_show_minta_unlock_button(object $inv): void
    {
        $this->assertSame(
            'Unlock',
            $inv->approval,
            'Pre-condition: invoice must have approval = Unlock'
        );

        $this->assertFalse(
            $this->shouldShowMintaUnlock($inv),
            "Invoice #{$inv->id} with approval='Unlock' must NOT show 'Minta Unlock' button"
        );
    }

    // -------------------------------------------------------------------------
    // Property 8c — Mutual exclusivity across all 100 varied inputs
    // Validates: Requirements 3.2, 3.3, 3.5, 11.2, 11.3, 11.4, 11.5
    // -------------------------------------------------------------------------

    /**
     * @dataProvider invoiceApprovalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice, "Edit"/"Lock" and "Minta Unlock" are mutually exclusive:
     * exactly one group is shown, never both.
     */
    public function test_edit_lock_and_minta_unlock_are_mutually_exclusive(object $inv): void
    {
        $showEdit        = $this->shouldShowEdit($inv);
        $showLock        = $this->shouldShowLock($inv);
        $showMintaUnlock = $this->shouldShowMintaUnlock($inv);

        // Edit and Lock always agree with each other
        $this->assertSame(
            $showEdit,
            $showLock,
            "shouldShowEdit and shouldShowLock must always return the same value"
        );

        // Edit/Lock and MintaUnlock are mutually exclusive
        $this->assertNotSame(
            $showEdit,
            $showMintaUnlock,
            "shouldShowEdit and shouldShowMintaUnlock must never both be true or both be false"
        );
    }

    /**
     * @dataProvider invoiceApprovalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval strictly = 'Unlock', all three conditions hold:
     * Edit=true, Lock=true, MintaUnlock=false.
     */
    public function test_unlock_approval_shows_correct_buttons(object $inv): void
    {
        if ($inv->approval !== 'Unlock') {
            $this->markTestSkipped("Skipping non-Unlock invoice (approval=" . var_export($inv->approval, true) . ")");
        }

        $this->assertTrue($this->shouldShowEdit($inv), 'Unlock invoice must show Edit');
        $this->assertTrue($this->shouldShowLock($inv), 'Unlock invoice must show Lock');
        $this->assertFalse($this->shouldShowMintaUnlock($inv), 'Unlock invoice must NOT show Minta Unlock');
    }

    /**
     * @dataProvider invoiceApprovalProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 8: Rendering daftar invoice — tombol aksi sesuai status approval
     *
     * For any invoice with approval strictly = 'Lock', all three conditions hold:
     * Edit=false, Lock=false, MintaUnlock=true.
     */
    public function test_lock_approval_shows_correct_buttons(object $inv): void
    {
        if ($inv->approval !== 'Lock') {
            $this->markTestSkipped("Skipping non-Lock invoice (approval=" . var_export($inv->approval, true) . ")");
        }

        $this->assertFalse($this->shouldShowEdit($inv), 'Lock invoice must NOT show Edit');
        $this->assertFalse($this->shouldShowLock($inv), 'Lock invoice must NOT show Lock');
        $this->assertTrue($this->shouldShowMintaUnlock($inv), 'Lock invoice must show Minta Unlock');
    }

    // -------------------------------------------------------------------------
    // Request Batal tests for Admin
    // -------------------------------------------------------------------------

    /**
     * @dataProvider invoiceApprovalProvider
     *
     * For any invoice with status != 'Batal', shouldShowRequestBatal must be true.
     * For any invoice with status = 'Batal', shouldShowRequestBatal must be false.
     */
    public function test_request_batal_shown_only_when_not_batal(object $inv): void
    {
        if ($inv->status === 'Batal') {
            $this->assertFalse(
                $this->shouldShowRequestBatal($inv),
                "Invoice with status='Batal' must NOT show 'Request Batal'"
            );
        } else {
            $this->assertTrue(
                $this->shouldShowRequestBatal($inv),
                "Invoice with status='{$inv->status}' must show 'Request Batal'"
            );
        }
    }

    // -------------------------------------------------------------------------
    // Request Delete tests (fitur dihapus)
    // -------------------------------------------------------------------------

    /**
     * Test Request Delete is removed/disabled for all roles.
     */
    public function test_request_delete_removed(): void
    {
        $this->assertFalse($this->shouldShowRequestDelete(true), 'Request Delete is removed for Admin');
        $this->assertFalse($this->shouldShowRequestDelete(false), 'Request Delete is removed for Non-Admin');
    }
}
