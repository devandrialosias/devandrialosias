<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/includes/database.php';

$pdo = database();
$loginError = '';
$message = '';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $statement = $pdo->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $statement->execute([$username]);
    $admin = $statement->fetch();

    $validPassword = $admin && (
        password_verify($password, $admin['password_hash'])
        || hash_equals((string) $admin['password_hash'], $password)
    );

    if ($validPassword) {
        if (!password_get_info($admin['password_hash'])['algo']) {
            $upgrade = $pdo->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?');
            $upgrade->execute([password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['display_name'];
        header('Location: admin.php');
        exit;
    }

    $loginError = 'Username atau password salah.';
}

if (!isset($_SESSION['admin_id'])):
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Devandria Losias</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #09090b; color: #f5f5f5; font-family: Arial, sans-serif; }
        .login-box { width: min(100%, 400px); padding: 32px; border: 1px solid #29292e; border-radius: 16px; background: #151517; }
        h1 { margin: 0 0 8px; font-size: 25px; }
        p { color: #92929a; }
        label { display: block; margin: 18px 0 7px; color: #bdbdc4; font-size: 13px; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #303036; border-radius: 9px; background: #0f0f11; color: white; }
        button { width: 100%; margin-top: 22px; padding: 12px; border: 0; border-radius: 9px; background: #ffbf00; color: #111; font-weight: 700; cursor: pointer; }
        .error { padding: 10px 12px; border-radius: 8px; background: #481d1d; color: #ffaaa5; }
    </style>
</head>
<body>
    <main class="login-box">
        <h1>Admin Super</h1>
        <p>Masuk untuk mengelola isi portfolio.</p>
        <?php if ($loginError): ?><div class="error"><?php echo htmlspecialchars($loginError); ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="action" value="login">
            <label for="username">Username</label>
            <input id="username" name="username" autocomplete="username" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
<?php
exit;
endif;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function updateContent(PDO $pdo, array $values): void
{
    $statement = $pdo->prepare('UPDATE site_content SET content_value = ? WHERE content_key = ?');
    foreach ($values as $key => $value) {
        $statement->execute([trim((string) $value), $key]);
    }
}

function uploadSkillIcon(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    $filename = 'skill-' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = __DIR__ . '/assets/img/skills/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'assets/img/skills/' . $filename;
}

function uploadCareerLogo(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    $filename = 'career-' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = __DIR__ . '/assets/img/careers/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'assets/img/careers/' . $filename;
}

function uploadEducationLogo(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    $filename = 'education-' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = __DIR__ . '/assets/img/education/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'assets/img/education/' . $filename;
}

function uploadProjectImage(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    $filename = 'project-' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = __DIR__ . '/assets/img/projects/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'assets/img/projects/' . $filename;
}

function uploadAchievementImage(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        return null;
    }

    $filename = 'achievement-' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = __DIR__ . '/assets/img/achievements/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'assets/img/achievements/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['delete_action'] ?? ($_POST['action'] ?? '');

    $deleteTables = [
        'delete_skills' => 'skills',
        'delete_career' => 'careers',
        'delete_education' => 'education',
        'delete_achievements' => 'achievements',
        'delete_projects' => 'projects',
    ];

    if (isset($deleteTables[$action])) {
        $selectedIds = array_values(array_filter(array_map('intval', $_POST['selected_ids'] ?? []), static fn(int $id): bool => $id > 0));
        if ($selectedIds) {
            $statement = $pdo->prepare('DELETE FROM ' . $deleteTables[$action] . ' WHERE id = ?');
            foreach ($selectedIds as $id) {
                $statement->execute([$id]);
            }
            $message = 'Data terpilih berhasil dihapus.';
        } else {
            $message = 'Pilih setidaknya satu data untuk dihapus.';
        }
    }

    if ($action === 'save_content') {
        updateContent($pdo, $_POST['content'] ?? []);
        if (!empty($_FILES['profile_photo']['tmp_name']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $destination = __DIR__ . '/assets/img/profile.' . $extension;
                move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination);
                updateContent($pdo, ['profile_photo' => 'assets/img/profile.' . $extension]);
            }
        }
        $message = 'Profil dan konten halaman berhasil disimpan.';
    }

    if ($action === 'save_skills') {
        $statement = $pdo->prepare('UPDATE skills SET name = ?, icon_class = ?, category = ? WHERE id = ?');
        foreach ($_POST['skills'] ?? [] as $id => $skill) {
            $iconFile = [
                'name' => $_FILES['skills']['name'][$id]['icon_file'] ?? '',
                'tmp_name' => $_FILES['skills']['tmp_name'][$id]['icon_file'] ?? '',
                'error' => $_FILES['skills']['error'][$id]['icon_file'] ?? UPLOAD_ERR_NO_FILE,
            ];
            $icon = uploadSkillIcon($iconFile);
            $statement->execute([$skill['name'], $icon ?? ($skill['icon_class'] ?? 'fa-solid fa-star'), $skill['category'], (int) $id]);
        }
        $message = 'Keahlian berhasil disimpan.';
    }

    if ($action === 'add_skill') {
        $skill = $_POST['skill'] ?? [];
        $icon = uploadSkillIcon([
            'name' => $_FILES['skill']['name']['icon_file'] ?? '',
            'tmp_name' => $_FILES['skill']['tmp_name']['icon_file'] ?? '',
            'error' => $_FILES['skill']['error']['icon_file'] ?? UPLOAD_ERR_NO_FILE,
        ]);
        $statement = $pdo->prepare('INSERT INTO skills (name, icon_class, category, sort_order) VALUES (?, ?, ?, ?)');
        $statement->execute([$skill['name'], $icon ?? 'fa-solid fa-star', $skill['category'], (int) $skill['sort_order']]);
        $message = 'Keahlian baru berhasil ditambahkan.';
    }

    if ($action === 'save_career') {
        $career = reset($_POST['career']);
        $careerFile = [
            'name' => $_FILES['career']['name'][0]['logo_file'] ?? '',
            'tmp_name' => $_FILES['career']['tmp_name'][0]['logo_file'] ?? '',
            'error' => $_FILES['career']['error'][0]['logo_file'] ?? UPLOAD_ERR_NO_FILE,
        ];
        $logo = uploadCareerLogo($careerFile);
        $statement = $pdo->prepare('UPDATE careers SET title = ?, company = ?, location = ?, logo_path = COALESCE(?, logo_path), description = ? WHERE id = ?');
        $statement->execute([$career['title'], $career['company'], $career['location'], $logo, $career['description'], (int) $career['id']]);
        $message = 'Data karier berhasil disimpan.';
    }

    if ($action === 'add_career') {
        $careerInput = $_POST['career'] ?? [];
        $logo = uploadCareerLogo([
            'name' => $_FILES['career']['name']['logo_file'] ?? '',
            'tmp_name' => $_FILES['career']['tmp_name']['logo_file'] ?? '',
            'error' => $_FILES['career']['error']['logo_file'] ?? UPLOAD_ERR_NO_FILE,
        ]);
        $statement = $pdo->prepare('INSERT INTO careers (title, company, location, start_date, end_date, badge, logo_path, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$careerInput['title'], $careerInput['company'], $careerInput['location'], '', '', '', $logo, $careerInput['description'], (int) $careerInput['sort_order']]);
        $message = 'Karier baru berhasil ditambahkan.';
    }

    if ($action === 'save_education') {
        $statement = $pdo->prepare('UPDATE education SET institution = ?, degree = ?, location = ?, start_date = ?, end_date = ?, logo_path = COALESCE(?, logo_path), description = ? WHERE id = ?');
        foreach ($_POST['education'] ?? [] as $id => $item) {
            $logo = uploadEducationLogo([
                'name' => $_FILES['education']['name'][$id]['logo_file'] ?? '',
                'tmp_name' => $_FILES['education']['tmp_name'][$id]['logo_file'] ?? '',
                'error' => $_FILES['education']['error'][$id]['logo_file'] ?? UPLOAD_ERR_NO_FILE,
            ]);
            $statement->execute([$item['institution'], $item['degree'], $item['location'], $item['start_date'], $item['end_date'], $logo, $item['description'], (int) $id]);
        }
        $message = 'Pendidikan berhasil disimpan.';
    }

    if ($action === 'add_education') {
        $item = $_POST['education'] ?? [];
        $logo = uploadEducationLogo([
            'name' => $_FILES['education']['name']['logo_file'] ?? '',
            'tmp_name' => $_FILES['education']['tmp_name']['logo_file'] ?? '',
            'error' => $_FILES['education']['error']['logo_file'] ?? UPLOAD_ERR_NO_FILE,
        ]);
        $statement = $pdo->prepare('INSERT INTO education (institution, degree, location, start_date, end_date, logo_path, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([$item['institution'], $item['degree'], $item['location'], $item['start_date'], $item['end_date'], $logo, $item['description'], (int) $item['sort_order']]);
        $message = 'Pendidikan baru berhasil ditambahkan.';
    }

    if ($action === 'save_achievements') {
        $statement = $pdo->prepare('UPDATE achievements SET certificate_code = ?, title = ?, issuer = ?, category = ?, tags = ?, published_label = ? WHERE id = ?');
        foreach ($_POST['achievements'] ?? [] as $id => $item) {
            $statement->execute([$item['certificate_code'], $item['title'], $item['issuer'], $item['category'], $item['tags'], $item['published_label'], (int) $id]);
        }
        $message = 'Prestasi berhasil disimpan.';
    }

    if ($action === 'add_achievement') {
        $achievement = $_POST['achievement'] ?? [];
        $imagePath = uploadAchievementImage($_FILES['achievement_image'] ?? []);
        $sortOrder = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM achievements')->fetchColumn();
        $statement = $pdo->prepare('INSERT INTO achievements (certificate_code, title, issuer, category, tags, image_path, published_label, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute(['', $achievement['title'], $achievement['issuer'], $achievement['category'], '', $imagePath, '', $sortOrder]);
        $message = 'Prestasi baru berhasil ditambahkan.';
    }

    if ($action === 'save_projects') {
        $statement = $pdo->prepare('UPDATE projects SET title = ?, description = ?, technologies = ? WHERE id = ?');
        foreach ($_POST['projects'] ?? [] as $id => $item) {
            $statement->execute([$item['title'], $item['description'], $item['technologies'], (int) $id]);
        }
        $message = 'Proyek berhasil disimpan.';
    }

    if ($action === 'add_project') {
        $project = $_POST['project'] ?? [];
        $imagePath = uploadProjectImage($_FILES['project_image'] ?? []);
        $sortOrder = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM projects')->fetchColumn();
        $statement = $pdo->prepare('INSERT INTO projects (title, description, image_path, technologies, sort_order) VALUES (?, ?, ?, ?, ?)');
        $statement->execute([$project['title'], $project['description'], $imagePath, '', $sortOrder]);
        $message = 'Proyek baru berhasil ditambahkan.';
    }

    if ($action === 'save_contacts') {
        $statement = $pdo->prepare('UPDATE contact_links SET title = ?, description = ?, url = ? WHERE id = ?');
        foreach ($_POST['contacts'] ?? [] as $id => $item) {
            $statement->execute([$item['title'], $item['description'], $item['url'], (int) $id]);
        }
        $message = 'Kontak berhasil disimpan.';
    }
}

$contentRows = $pdo->query('SELECT content_key, content_value FROM site_content')->fetchAll();
$content = [];
foreach ($contentRows as $row) $content[$row['content_key']] = $row['content_value'];
$skills = $pdo->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
$career = $pdo->query('SELECT * FROM careers ORDER BY sort_order, id LIMIT 1')->fetch();
$education = $pdo->query('SELECT * FROM education ORDER BY sort_order, id')->fetchAll();
$achievements = $pdo->query('SELECT * FROM achievements ORDER BY sort_order, id')->fetchAll();
$projects = $pdo->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$contacts = $pdo->query('SELECT * FROM contact_links ORDER BY sort_order, id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Super | Devandria Losias</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #09090b; color: #f1f1f1; font-family: Arial, sans-serif; }
        header { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 22px max(20px, calc((100% - 1180px) / 2)); border-bottom: 1px solid #242428; background: #111113; }
        header h1 { margin: 0; font-size: 22px; }
        header a { color: #ffbf00; text-decoration: none; font-size: 13px; }
        main { width: min(100% - 40px, 1180px); margin: 28px auto 70px; }
        .notice { margin-bottom: 20px; padding: 12px 15px; border: 1px solid #3f370f; border-radius: 9px; background: #29230b; color: #ffd84d; }
        .panel { margin-top: 20px; padding: 22px; border: 1px solid #29292e; border-radius: 14px; background: #151517; }
        .panel h2 { margin: 0 0 18px; font-size: 18px; }
        .career-section > form + form, .education-section > form + form, .achievement-section > form + form, .project-section > form + form { margin-top: 24px; padding-top: 22px; border-top: 1px solid #29292e; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .field { display: grid; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { color: #a8a8b0; font-size: 12px; }
        input, textarea, select { width: 100%; padding: 10px 12px; border: 1px solid #303036; border-radius: 8px; background: #0f0f11; color: #f1f1f1; font: inherit; }
        textarea { min-height: 90px; resize: vertical; }
        button { border: 0; border-radius: 8px; padding: 10px 16px; background: #ffbf00; color: #111; font-weight: 700; cursor: pointer; }
        .save { margin-top: 18px; }
        .item { position: relative; margin-top: 12px; padding: 16px 16px 16px 48px; border: 1px solid #303036; border-radius: 10px; background: #1b1b1e; transition: border-color .15s ease, background .15s ease; }
        .item > input[type="checkbox"] { position: absolute; top: 19px; left: 16px; width: 17px; height: 17px; margin: 0; padding: 0; accent-color: #ffbf00; cursor: pointer; }
        .item:has(> input[type="checkbox"]:checked) { border-color: #ffbf00; background: #211f17; }
        .item.contact-item { border: 0; border-bottom: 1px solid #303036; border-radius: 0; background: transparent; }
        .item h3 { margin: 0 0 12px; font-size: 14px; color: #ffcf38; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
        .form-actions .save { margin-top: 0; }
        button.delete { background: #722d32; color: #fff; }
        .delete-dialog { width: min(100% - 32px, 420px); padding: 24px; border: 1px solid #45454c; border-radius: 12px; background: #19191c; color: #f1f1f1; box-shadow: 0 24px 70px rgba(0, 0, 0, .65); }
        .delete-dialog::backdrop { background: rgba(0, 0, 0, .72); backdrop-filter: blur(3px); }
        .delete-dialog h2 { margin: 0 0 10px; font-size: 19px; }
        .delete-dialog p { margin: 0; color: #b7b7bf; line-height: 1.5; }
        .dialog-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
        .dialog-actions button { margin: 0; }
        .dialog-actions .cancel { border: 1px solid #45454c; background: #252529; color: #f1f1f1; }
        @media (max-width: 700px) { .grid { grid-template-columns: 1fr; } .field.full { grid-column: auto; } header { align-items: flex-start; } }
    </style>
</head>
<body>
<header>
    <h1>Admin Super</h1>
    <a href="admin.php?logout=1">Keluar</a>
</header>
<main>
    <?php if ($message): ?><div class="notice"><?php echo e($message); ?></div><?php endif; ?>

    <form class="panel" method="post" enctype="multipart/form-data">
        <h2>Profil, Beranda, dan Tentang</h2>
        <input type="hidden" name="action" value="save_content">
        <div class="grid">
            <?php foreach (['profile_name' => 'Nama profil', 'profile_username' => 'Username', 'profile_location' => 'Alamat', 'home_title' => 'Judul beranda', 'about_signature' => 'Tanda tangan'] as $key => $label): ?>
                <div class="field"><label><?php echo e($label); ?></label><input name="content[<?php echo e($key); ?>]" value="<?php echo e($content[$key] ?? ''); ?>"></div>
            <?php endforeach; ?>
            <div class="field"><label>Foto profil</label><input type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp"></div>
            <?php foreach (['home_intro_1' => 'Deskripsi beranda', 'about_description' => 'Deskripsi tentang'] as $key => $label): ?>
                <div class="field full"><label><?php echo e($label); ?></label><textarea name="content[<?php echo e($key); ?>]"><?php echo e($content[$key] ?? ''); ?></textarea></div>
            <?php endforeach; ?>
        </div>
        <button class="save">Simpan konten</button>
    </form>

    <div class="panel">
        <h2>Keahlian</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_skills">
            <?php foreach ($skills as $skill): ?><div class="item"><input type="checkbox" name="selected_ids[]" value="<?php echo $skill['id']; ?>" aria-label="Pilih keahlian <?php echo e($skill['name']); ?>"><h3><?php echo e($skill['name']); ?></h3><input type="hidden" name="skills[<?php echo $skill['id']; ?>][icon_class]" value="<?php echo e($skill['icon_class'] ?? 'fa-solid fa-star'); ?>"><div class="grid"><div class="field"><label>Nama</label><input name="skills[<?php echo $skill['id']; ?>][name]" value="<?php echo e($skill['name']); ?>"></div><div class="field"><label>Kategori</label><input name="skills[<?php echo $skill['id']; ?>][category]" value="<?php echo e($skill['category']); ?>"></div><div class="field"><label>Upload foto</label><input type="file" name="skills[<?php echo $skill['id']; ?>][icon_file]" accept="image/jpeg,image/png,image/webp,image/gif"></div></div></div><?php endforeach; ?>
            <div class="form-actions"><button class="save">Simpan keahlian</button><?php if ($skills): ?><button class="delete" type="submit" name="delete_action" value="delete_skills" data-delete-form>Hapus terpilih</button><?php endif; ?></div>
        </form>
        <form class="add-panel" method="post" enctype="multipart/form-data">
            <h3>Tambah Keahlian</h3>
            <input type="hidden" name="action" value="add_skill">
            <input type="hidden" name="skill[sort_order]" value="99">
            <div class="grid"><div class="field"><label>Nama</label><input name="skill[name]" required></div><div class="field"><label>Kategori</label><input name="skill[category]" placeholder="backend, tools, database, frontend, main" required></div><div class="field"><label>Upload foto</label><input type="file" name="skill[icon_file]" accept="image/jpeg,image/png,image/webp,image/gif" required></div></div>
            <button class="save">Tambah keahlian</button>
        </form>
    </div>

    <section class="panel career-section" aria-label="Pengelolaan karier">
        <form method="post" enctype="multipart/form-data">
            <h2>Karier</h2>
            <input type="hidden" name="action" value="save_career">
            <?php if ($career): ?><div class="item career-item"><input type="checkbox" name="selected_ids[]" value="<?php echo $career['id']; ?>" aria-label="Pilih data karier"><input type="hidden" name="career[0][id]" value="<?php echo $career['id']; ?>"><div class="grid">
                <?php foreach (['title' => 'Judul', 'company' => 'Perusahaan', 'location' => 'Lokasi'] as $key => $label): ?><div class="field"><label><?php echo $label; ?></label><input name="career[0][<?php echo $key; ?>]" value="<?php echo e($career[$key]); ?>"></div><?php endforeach; ?>
                <div class="field"><label>Choose File logo</label><input type="file" name="career[0][logo_file]" accept="image/jpeg,image/png,image/webp,image/gif"></div><div class="field full"><label>Deskripsi</label><textarea name="career[0][description]"><?php echo e($career['description']); ?></textarea></div></div><div class="form-actions"><button class="save">Simpan karier</button><button class="delete" type="submit" name="delete_action" value="delete_career" data-delete-form>Hapus terpilih</button></div></div><?php endif; ?>
        </form>
        <form method="post" enctype="multipart/form-data"><h2>Tambah Karier</h2><input type="hidden" name="action" value="add_career"><input type="hidden" name="career[sort_order]" value="99"><div class="grid"><?php foreach (['title' => 'Judul', 'company' => 'Perusahaan', 'location' => 'Lokasi'] as $key => $label): ?><div class="field"><label><?php echo $label; ?></label><input name="career[<?php echo $key; ?>]" required></div><?php endforeach; ?><div class="field"><label>Choose File logo</label><input type="file" name="career[logo_file]" accept="image/jpeg,image/png,image/webp,image/gif"></div><div class="field full"><label>Deskripsi</label><textarea name="career[description]"></textarea></div></div><button class="save">Tambah karier</button></form>
    </section>

    <section class="panel education-section" aria-label="Pengelolaan pendidikan">
        <form method="post" enctype="multipart/form-data"><h2>Pendidikan</h2><input type="hidden" name="action" value="save_education"><?php foreach ($education as $item): ?><div class="item"><input type="checkbox" name="selected_ids[]" value="<?php echo $item['id']; ?>" aria-label="Pilih data pendidikan <?php echo e($item['institution']); ?>"><h3><?php echo e($item['institution']); ?></h3><div class="grid"><div class="field"><label>Institusi</label><input name="education[<?php echo $item['id']; ?>][institution]" value="<?php echo e($item['institution']); ?>"></div><div class="field"><label>Jurusan / Gelar</label><input name="education[<?php echo $item['id']; ?>][degree]" value="<?php echo e($item['degree']); ?>"></div><div class="field"><label>Lokasi</label><input name="education[<?php echo $item['id']; ?>][location]" value="<?php echo e($item['location']); ?>"></div><div class="field"><label>Mulai</label><input name="education[<?php echo $item['id']; ?>][start_date]" value="<?php echo e($item['start_date']); ?>"></div><div class="field"><label>Selesai</label><input name="education[<?php echo $item['id']; ?>][end_date]" value="<?php echo e($item['end_date']); ?>"></div><div class="field"><label>Choose File logo</label><input type="file" name="education[<?php echo $item['id']; ?>][logo_file]" accept="image/jpeg,image/png,image/webp,image/gif"></div><div class="field full"><label>Deskripsi</label><textarea name="education[<?php echo $item['id']; ?>][description]"><?php echo e($item['description']); ?></textarea></div></div></div><?php endforeach; ?><div class="form-actions"><button class="save">Simpan pendidikan</button><?php if ($education): ?><button class="delete" type="submit" name="delete_action" value="delete_education" data-delete-form>Hapus terpilih</button><?php endif; ?></div></form>
        <form method="post" enctype="multipart/form-data"><h2>Tambah Pendidikan</h2><input type="hidden" name="action" value="add_education"><input type="hidden" name="education[sort_order]" value="99"><div class="grid"><?php foreach (['institution' => 'Institusi', 'degree' => 'Jurusan / Gelar', 'location' => 'Lokasi', 'start_date' => 'Mulai', 'end_date' => 'Selesai'] as $key => $label): ?><div class="field"><label><?php echo $label; ?></label><input name="education[<?php echo $key; ?>]" required></div><?php endforeach; ?><div class="field"><label>Choose File logo</label><input type="file" name="education[logo_file]" accept="image/jpeg,image/png,image/webp,image/gif"></div><div class="field full"><label>Deskripsi</label><textarea name="education[description]"></textarea></div></div><button class="save">Tambah pendidikan</button></form>
    </section>

    <section class="panel achievement-section" aria-label="Pengelolaan prestasi">
        <form method="post"><h2>Prestasi</h2><input type="hidden" name="action" value="save_achievements"><?php foreach ($achievements as $item): ?><div class="item"><input type="checkbox" name="selected_ids[]" value="<?php echo $item['id']; ?>" aria-label="Pilih prestasi <?php echo e($item['title']); ?>"><h3><?php echo e($item['title']); ?></h3><div class="grid"><?php foreach (['certificate_code' => 'Kode', 'title' => 'Judul', 'issuer' => 'Penerbit', 'category' => 'Kategori', 'tags' => 'Tag', 'published_label' => 'Tanggal'] as $key => $label): ?><div class="field"><label><?php echo $label; ?></label><input name="achievements[<?php echo $item['id']; ?>][<?php echo $key; ?>]" value="<?php echo e($item[$key]); ?>"></div><?php endforeach; ?></div></div><?php endforeach; ?><div class="form-actions"><button class="save">Simpan prestasi</button><?php if ($achievements): ?><button class="delete" type="submit" name="delete_action" value="delete_achievements" data-delete-form>Hapus terpilih</button><?php endif; ?></div></form>
        <form method="post" enctype="multipart/form-data"><h2>Tambah Prestasi</h2><input type="hidden" name="action" value="add_achievement"><div class="grid"><?php foreach (['title' => 'Judul', 'issuer' => 'Penerbit', 'category' => 'Kategori'] as $key => $label): ?><div class="field"><label><?php echo $label; ?></label><input name="achievement[<?php echo $key; ?>]" required></div><?php endforeach; ?><div class="field"><label>Gambar prestasi</label><input type="file" name="achievement_image" accept="image/jpeg,image/png,image/webp,image/gif"></div></div><button class="save">Tambah prestasi</button></form>
    </section>

    <section class="panel project-section" aria-label="Pengelolaan proyek">
        <form method="post"><h2>Proyek</h2><input type="hidden" name="action" value="save_projects"><?php foreach ($projects as $item): ?><div class="item"><input type="checkbox" name="selected_ids[]" value="<?php echo $item['id']; ?>" aria-label="Pilih proyek <?php echo e($item['title']); ?>"><h3><?php echo e($item['title']); ?></h3><div class="grid"><div class="field"><label>Judul</label><input name="projects[<?php echo $item['id']; ?>][title]" value="<?php echo e($item['title']); ?>"></div><div class="field"><label>Teknologi, pisahkan koma</label><input name="projects[<?php echo $item['id']; ?>][technologies]" value="<?php echo e($item['technologies']); ?>"></div><div class="field full"><label>Deskripsi</label><textarea name="projects[<?php echo $item['id']; ?>][description]"><?php echo e($item['description']); ?></textarea></div></div></div><?php endforeach; ?><div class="form-actions"><button class="save">Simpan proyek</button><?php if ($projects): ?><button class="delete" type="submit" name="delete_action" value="delete_projects" data-delete-form>Hapus terpilih</button><?php endif; ?></div></form>
        <form method="post" enctype="multipart/form-data"><h2>Tambah Proyek</h2><input type="hidden" name="action" value="add_project"><div class="grid"><div class="field"><label>Judul</label><input name="project[title]" required></div><div class="field"><label>Gambar proyek</label><input type="file" name="project_image" accept="image/jpeg,image/png,image/webp,image/gif" required></div><div class="field full"><label>Deskripsi</label><textarea name="project[description]" required></textarea></div></div><button class="save">Tambah proyek</button></form>
    </section>

    <form class="panel" method="post"><h2>Kontak</h2><input type="hidden" name="action" value="save_contacts"><?php foreach ($contacts as $item): ?><div class="item contact-item"><h3><?php echo e($item['platform']); ?></h3><div class="grid"><div class="field"><label>Judul</label><input name="contacts[<?php echo $item['id']; ?>][title]" value="<?php echo e($item['title']); ?>"></div><div class="field"><label>URL</label><input name="contacts[<?php echo $item['id']; ?>][url]" value="<?php echo e($item['url']); ?>"></div><div class="field full"><label>Deskripsi</label><textarea name="contacts[<?php echo $item['id']; ?>][description]"><?php echo e($item['description']); ?></textarea></div></div></div><?php endforeach; ?><button class="save">Simpan kontak</button></form>
</main>
<dialog class="delete-dialog" id="delete-confirmation" aria-labelledby="delete-dialog-title" aria-describedby="delete-dialog-message">
    <h2 id="delete-dialog-title">Konfirmasi penghapusan</h2>
    <p id="delete-dialog-message"></p>
    <div class="dialog-actions">
        <button class="cancel" type="button" data-dialog-cancel>Batal</button>
        <button class="delete" type="button" data-dialog-confirm>Hapus</button>
    </div>
</dialog>
<script>
    const deleteDialog = document.getElementById('delete-confirmation');
    const deleteDialogMessage = document.getElementById('delete-dialog-message');
    const deleteDialogConfirm = deleteDialog.querySelector('[data-dialog-confirm]');
    let pendingDelete = null;
    let submittingDelete = false;

    document.querySelectorAll('[data-delete-form]').forEach((button) => {
        button.form.addEventListener('submit', (event) => {
            if (event.submitter !== button) return;
            if (submittingDelete) {
                submittingDelete = false;
                return;
            }

            event.preventDefault();
            const selected = button.form.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (!selected.length) {
                pendingDelete = null;
                deleteDialogMessage.textContent = 'Pilih setidaknya satu data sebelum menghapus.';
                deleteDialogConfirm.hidden = true;
                deleteDialog.showModal();
                return;
            }

            pendingDelete = { form: button.form, button };
            deleteDialogMessage.textContent = `Hapus ${selected.length} data yang dipilih? Tindakan ini tidak dapat dibatalkan.`;
            deleteDialogConfirm.hidden = false;
            deleteDialog.showModal();
        });
    });

    deleteDialog.querySelector('[data-dialog-cancel]').addEventListener('click', () => deleteDialog.close());
    deleteDialogConfirm.addEventListener('click', () => {
        if (!pendingDelete) return;
        const { form, button } = pendingDelete;
        pendingDelete = null;
        deleteDialog.close();
        submittingDelete = true;
        form.requestSubmit(button);
    });

    deleteDialog.addEventListener('close', () => {
        pendingDelete = null;
        deleteDialogConfirm.hidden = false;
    });
</script>
</body>
</html>
