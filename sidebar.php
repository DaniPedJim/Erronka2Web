<?php
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administratzailea') {
?>
    <nav class="admin-sidebar">
        <div class="sidebar-header">
        </div>
        <ul>
           <a href="admin_gehitu_produktua.php">➕ Sartu produktua</a>

            <a href="admin_editatu_produktua.php">📝 Editatu produktua</a>

            <a href="admin_ezabatu_produktua.php">🗑️ Ezabatu produktua</a>

            <a href="admin_salmenta_historiala.php">📊 Salmenta historiala</a>
        </ul>
    </nav>
<?php
}
?>