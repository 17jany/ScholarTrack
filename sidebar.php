<?php
/*
|--------------------------------------------------------------------------
| One-Time Sidebar Fix
|--------------------------------------------------------------------------
| Place this file in the SAME folder as the active header.php, then open:
| http://localhost/GUELAS/ScholarTrack/uploads/fix_sidebar.php
|
| After it says SUCCESS, delete this file.
|--------------------------------------------------------------------------
*/

$headerPath = __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
$backupPath = __DIR__ . DIRECTORY_SEPARATOR . 'header_backup_before_password.php';

if (!file_exists($headerPath)) {
    exit('ERROR: header.php was not found in this folder: ' . __DIR__);
}

$code = file_get_contents($headerPath);

if ($code === false) {
    exit('ERROR: Unable to read header.php');
}

if (substr_count($code, 'href="change_password.php"') >= 2) {
    exit('DONE: Change Password is already present twice in this header.php. Try Ctrl+F5.');
}

if (!file_exists($backupPath)) {
    if (!copy($headerPath, $backupPath)) {
        exit('ERROR: Unable to create a backup file.');
    }
}

$passwordMenu = <<<'HTML'

                    <li>
                        <a
                            href="change_password.php"
                            class="<?= $current_page === "change_password.php"
                                ? "active"
                                : ""; ?>"
                        >
                            <span class="menu-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10h14V10a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4V6Zm3 9.7V18h-2v-2.3a2 2 0 1 1 2 0Z"/>
                                </svg>
                            </span>

                            <span>Change Password</span>
                        </a>
                    </li>

HTML;

$pattern = '/(\s*<li>\s*<a\s+href=["\']logout\.php["\']\s*>)/i';

$updated = preg_replace(
    $pattern,
    $passwordMenu . '$1',
    $code,
    2,
    $count
);

if ($updated === null) {
    exit('ERROR: Regex processing failed.');
}

if ($count !== 2) {
    exit(
        'ERROR: Expected 2 Logout menu items but found ' .
        $count .
        '. No changes were saved.'
    );
}

if (file_put_contents($headerPath, $updated) === false) {
    exit('ERROR: Unable to save the updated header.php');
}

echo '<h2 style="font-family:Arial;color:green;">SUCCESS</h2>';
echo '<p style="font-family:Arial;">Change Password was added before Logout in both admin and student sidebars.</p>';
echo '<p style="font-family:Arial;">Backup created as: <strong>header_backup_before_password.php</strong></p>';
echo '<p style="font-family:Arial;">Now delete <strong>fix_sidebar.php</strong>, return to the dashboard, and press <strong>Ctrl + F5</strong>.</p>';