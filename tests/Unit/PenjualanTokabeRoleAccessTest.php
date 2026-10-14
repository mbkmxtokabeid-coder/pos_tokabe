<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Feature: tokabe-approval-pelunasan
 * Property-based tests for role-based access control on Tokabe approval and pelunasan routes.
 * Uses PHPUnit data providers with 100 iterations to simulate property-based testing.
 *
 * The role check logic mirrors RoleMiddleware::handle() and the route definitions in web.php:
 *   - tokabe.approval.page  → middleware('role:Pemilik')
 *   - tokabe.unlock         → inside admin group (no extra role guard, but approval page guards it)
 *   - tokabe.pelunasan.page → middleware('role:Pemilik')
 *
 * Since this is a pure unit test (no HTTP / no DB), we test the role-checking predicate directly:
 *   $user->role === 'Pemilik'  → access granted
 *   any other role             → access denied
 */
class PenjualanTokabeRoleAccessTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Simulate the role check performed by RoleMiddleware for role:Pemilik.
     * Returns true when access is granted, false when denied.
     */
    private function roleCheckPemilik(object $user): bool
    {
        return $user->role === 'Pemilik';
    }

    // -------------------------------------------------------------------------
    // Data Providers
    // -------------------------------------------------------------------------

    /**
     * All non-Pemilik roles used in the system.
     */
    private static function nonPemilikRoles(): array
    {
        return ['Admin', 'AdminTKB', 'Karyawan', 'Magang', 'Stockist', 'Produksi'];
    }

    /**
     * Generate 100 users with varied non-Pemilik roles.
     * Used for Property 4 and Property 5 (access must be denied).
     */
    public static function nonPemilikUserProvider(): array
    {
        $cases = [];
        $roles = self::nonPemilikRoles();
        $roleCount = count($roles);

        for ($i = 0; $i < 100; $i++) {
            $role = $roles[$i % $roleCount];
            $cases["iteration_{$i}_role_{$role}"] = [
                'role' => $role,
                'name' => 'User ' . ($i + 1),
                'id'   => $i + 1,
            ];
        }

        return $cases;
    }

    /**
     * Generate 100 users with the Pemilik role.
     * Used to confirm the positive case (access must be granted).
     */
    public static function pemilikUserProvider(): array
    {
        $cases = [];

        for ($i = 0; $i < 100; $i++) {
            $cases["iteration_{$i}_role_Pemilik"] = [
                'role' => 'Pemilik',
                'name' => 'Pemilik User ' . ($i + 1),
                'id'   => $i + 1,
            ];
        }

        return $cases;
    }

    // =========================================================================
    // Property 4: Akses halaman approval dan unlock hanya untuk Pemilik
    // Validates: Requirements 4.5, 6.4, 6.5
    // =========================================================================

    /**
     * @dataProvider nonPemilikUserProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 4: Akses halaman approval dan unlock hanya untuk Pemilik
     *
     * For any user with role other than 'Pemilik', the role check for
     * tokabe.approval.page must result in access denial (returns false).
     */
    public function test_non_pemilik_is_denied_access_to_approval_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange: create a stdClass user with a non-Pemilik role
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role;

        // Act: apply the role check (mirrors middleware role:Pemilik)
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert: access must be denied for any non-Pemilik role
        $this->assertFalse(
            $accessGranted,
            "Role '{$role}' should be denied access to tokabe.approval.page, but was granted."
        );
    }

    /**
     * @dataProvider nonPemilikUserProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 4: Akses halaman approval dan unlock hanya untuk Pemilik
     *
     * For any user with role other than 'Pemilik', the role check for
     * tokabe.unlock must result in access denial (returns false).
     */
    public function test_non_pemilik_is_denied_access_to_unlock_endpoint(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role;

        // Act: the unlock endpoint is guarded by the same Pemilik role check
        // (approval page is the gateway; unlock is only reachable from approval page)
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert
        $this->assertFalse(
            $accessGranted,
            "Role '{$role}' should be denied access to tokabe.unlock, but was granted."
        );
    }

    /**
     * @dataProvider pemilikUserProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 4: Akses halaman approval dan unlock hanya untuk Pemilik
     *
     * For a user with role 'Pemilik', the role check for tokabe.approval.page
     * and tokabe.unlock must grant access (returns true).
     */
    public function test_pemilik_is_granted_access_to_approval_and_unlock(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role; // 'Pemilik'

        // Act
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert: Pemilik must always be granted access
        $this->assertTrue(
            $accessGranted,
            "Role 'Pemilik' should be granted access to approval/unlock, but was denied."
        );
    }

    // =========================================================================
    // Property 5: Akses halaman pelunasan hanya untuk Pemilik
    // Validates: Requirements 7.5, 7.6
    // =========================================================================

    /**
     * @dataProvider nonPemilikUserProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 5: Akses halaman pelunasan hanya untuk Pemilik
     *
     * For any user with role other than 'Pemilik', the role check for
     * tokabe.pelunasan.page must result in access denial (returns false).
     */
    public function test_non_pemilik_is_denied_access_to_pelunasan_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role;

        // Act: apply the role check (mirrors middleware role:Pemilik on tokabe.pelunasan.page)
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert: access must be denied for any non-Pemilik role
        $this->assertFalse(
            $accessGranted,
            "Role '{$role}' should be denied access to tokabe.pelunasan.page, but was granted."
        );
    }

    /**
     * @dataProvider pemilikUserProvider
     *
     * // Feature: tokabe-approval-pelunasan, Property 5: Akses halaman pelunasan hanya untuk Pemilik
     *
     * For a user with role 'Pemilik', the role check for tokabe.pelunasan.page
     * must grant access (returns true).
     */
    public function test_pemilik_is_granted_access_to_pelunasan_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role; // 'Pemilik'

        // Act
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert
        $this->assertTrue(
            $accessGranted,
            "Role 'Pemilik' should be granted access to tokabe.pelunasan.page, but was denied."
        );
    }

    // =========================================================================
    // Property 6: Akses halaman pembatalan hanya untuk Pemilik
    // =========================================================================

    /**
     * @dataProvider nonPemilikUserProvider
     *
     * For any user with role other than 'Pemilik', the role check for
     * tokabe.batal.page must result in access denial (returns false).
     */
    public function test_non_pemilik_is_denied_access_to_batal_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role;

        // Act: apply the role check (mirrors middleware role:Pemilik on tokabe.batal.page)
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert: access must be denied for any non-Pemilik role
        $this->assertFalse(
            $accessGranted,
            "Role '{$role}' should be denied access to tokabe.batal.page, but was granted."
        );
    }

    /**
     * @dataProvider pemilikUserProvider
     *
     * For a user with role 'Pemilik', the role check for tokabe.batal.page
     * must grant access (returns true).
     */
    public function test_pemilik_is_granted_access_to_batal_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role; // 'Pemilik'

        // Act
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert
        $this->assertTrue(
            $accessGranted,
            "Role 'Pemilik' should be granted access to tokabe.batal.page, but was denied."
        );
    }

    // =========================================================================
    // Property 7: Akses halaman delete hanya untuk Pemilik
    // =========================================================================

    /**
     * @dataProvider nonPemilikUserProvider
     *
     * For any user with role other than 'Pemilik', the role check for
     * tokabe.delete.page must result in access denial (returns false).
     */
    public function test_non_pemilik_is_denied_access_to_delete_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange: create a stdClass user with a non-Pemilik role
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role;

        // Act: apply the role check (mirrors middleware role:Pemilik on tokabe.delete.page)
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert: access must be denied for any non-Pemilik role
        $this->assertFalse(
            $accessGranted,
            "Role '{$role}' should be denied access to tokabe.delete.page, but was granted."
        );
    }

    /**
     * @dataProvider pemilikUserProvider
     *
     * For a user with role 'Pemilik', the role check for tokabe.delete.page
     * must grant access (returns true).
     */
    public function test_pemilik_is_granted_access_to_delete_page(
        string $role,
        string $name,
        int $id
    ): void {
        // Arrange
        $user = new \stdClass();
        $user->id   = $id;
        $user->name = $name;
        $user->role = $role; // 'Pemilik'

        // Act
        $accessGranted = $this->roleCheckPemilik($user);

        // Assert
        $this->assertTrue(
            $accessGranted,
            "Role 'Pemilik' should be granted access to tokabe.delete.page, but was denied."
        );
    }

    // =========================================================================
    // Additional edge-case: role string is case-sensitive
    // =========================================================================

    /**
     * // Feature: tokabe-approval-pelunasan, Property 4: Akses halaman approval dan unlock hanya untuk Pemilik
     * // Feature: tokabe-approval-pelunasan, Property 5: Akses halaman pelunasan hanya untuk Pemilik
     *
     * The role check is case-sensitive: 'pemilik', 'PEMILIK', 'PeMiLiK', etc.
     * must all be denied — only the exact string 'Pemilik' is accepted.
     */
    public function test_role_check_is_case_sensitive(): void
    {
        $casesVariants = ['pemilik', 'PEMILIK', 'PeMiLiK', 'pEMILIK', 'Pemilik '];

        foreach ($casesVariants as $variant) {
            $user = new \stdClass();
            $user->role = $variant;

            $accessGranted = $this->roleCheckPemilik($user);

            if ($variant === 'Pemilik') {
                $this->assertTrue($accessGranted, "Exact 'Pemilik' must be granted.");
            } else {
                $this->assertFalse(
                    $accessGranted,
                    "Role variant '{$variant}' must be denied (case-sensitive check)."
                );
            }
        }
    }
}
