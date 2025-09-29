<form action="reject_testimony.php" method="POST"
    onsubmit="return confirm('Are you sure you want to reject this testimony?');" class="d-inline">

    <!-- CSRF Protection Token -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

    <!-- Testimony Identification -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($testimony['id'] ?? '') ?>">
    <input type="hidden" name="token" value="<?= htmlspecialchars($testimony['approval_token'] ?? '') ?>">

    <!-- Reject Button -->
    <button type="submit" class="btn btn-sm btn-outline-danger ml-2" title="Reject Testimony" style="min-width: 100px;">
        <i class="fas fa-times-circle mr-2"></i> Reject
    </button>
</form>