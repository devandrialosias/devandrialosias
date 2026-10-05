<?php

require_once __DIR__ . '/includes/database.php';

$contentRows = database()->query('SELECT content_key, content_value FROM site_content')->fetchAll();
$content = [];
foreach ($contentRows as $row) {
    $content[$row['content_key']] = $row['content_value'];
}
$skills = database()->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
$skillCategories = [];
foreach ($skills as $skill) {
    $category = strtolower(trim($skill['category']));
    if ($category !== '') {
        $skillCategories[$category] = ($skillCategories[$category] ?? 0) + 1;
    }
}
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = $content['home_title'];

include "includes/header.php";
include "includes/sidebar.php";

?>

<main class="content">

    <section class="hero">

        <h1>
            <?php echo $escape($content['home_title']); ?>
        </h1>

        <div class="location">

            <span class="status-dot"></span>

            <i class="fa-solid fa-location-dot"></i>

            <span>
                <?php echo $escape($content['profile_location'] ?? 'Berbasis di Ciamis, Indonesia'); ?>
            </span>

            <small>ID</small>

        </div>


        <div class="hero-text">

            <?php if (!empty($content['home_intro_1'])): ?>
                <p><?php echo nl2br($escape($content['home_intro_1'])); ?></p>
            <?php endif; ?>

        </div>

    </section>


    <div class="section-divider"></div>


    <!-- Skill -->
    <section>

        <div class="section-heading">

            <h2>
                <i class="fa-solid fa-code"></i>
                Keahlian
            </h2>

            <p>
                Keahlian profesional saya.
            </p>

        </div>


        <div class="skill-filter">

            <button class="selected" data-filter="all">
                Semua
                <span><?php echo count($skills); ?></span>
            </button>

            <?php foreach ($skillCategories as $category => $count): ?>
                <button data-filter="<?php echo $escape($category); ?>">
                    <?php echo $escape(ucfirst($category)); ?>
                    <span><?php echo $count; ?></span>
                </button>
            <?php endforeach; ?>

        </div>


        <div class="skills">
            <?php foreach ($skills as $skill): ?>
                <span data-skill-category="<?php echo $escape($skill['category']); ?>">
                    <?php if (str_starts_with($skill['icon_class'], 'assets/img/')): ?>
                        <img class="skill-image" src="<?php echo $escape($skill['icon_class']); ?>" alt="">
                    <?php else: ?>
                        <i class="<?php echo $escape($skill['icon_class']); ?>"></i>
                    <?php endif; ?>
                    <?php echo $escape($skill['name']); ?>
                </span>
            <?php endforeach; ?>
        </div>

    </section>

</main>

<?php include "includes/footer.php"; ?>