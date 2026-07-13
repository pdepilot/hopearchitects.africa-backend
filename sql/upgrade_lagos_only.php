<?php
/**
 * Remove Abuja service areas and set Lagos-only copy.
 * Run once: php sql/upgrade_lagos_only.php
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/ServiceAreaRepository.php';

try {
    $pdo = getDatabaseConnection();

    ServiceAreaRepository::saveSection([
        'intro'    => "From Victoria Island to Lekki and the Mainland — we help clients design and build across Lagos's most sought-after districts.",
        'ctaText'  => "Don't see your district? Our teams cover all of Lagos.",
        'ctaLink'  => 'contact.php',
        'ctaLabel' => 'Book a Site Consultation',
    ]);
    echo "Updated service areas section copy (Lagos only).\n";

    $pdo->exec(
        "DELETE FROM service_areas WHERE
            LOWER(title) LIKE '%abuja%'
            OR LOWER(tag) LIKE '%abuja%'
            OR LOWER(description) LIKE '%abuja%'
            OR LOWER(title) LIKE '%maitama%'
            OR LOWER(title) LIKE '%gwarinpa%'
            OR LOWER(title) LIKE '%wuse%'
            OR LOWER(title) LIKE '%asokoro%'"
    );
    echo "Removed Abuja districts from service_areas.\n";

    $count = (int) $pdo->query('SELECT COUNT(*) FROM service_areas')->fetchColumn();
    if ($count === 0) {
        $areas = [
            ['Victoria Island & Ikoyi', 'Lagos Premium', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80', 'Luxury high-rises, waterfront estates, and executive residences in Lagos Island\'s most prestigious addresses.', 'business-outline', 'Premium towers', 'trending-up-outline', 'High demand', 'property.php', 1],
            ['Lekki & Ajah Corridor', 'Lagos Growth', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80', 'Modern estates, smart homes, and mixed-use developments along Lagos\' fastest-growing coastal corridor.', 'home-outline', 'Estates & villas', 'key-outline', 'Rent & sale', 'property.php', 2],
            ['Ikeja GRA & Mainland', 'Lagos Commercial', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=80', 'Commercial plazas, office blocks, and residential towers in Lagos mainland business hubs.', 'storefront-outline', 'Mixed-use', 'shield-checkmark-outline', 'Verified titles', 'property.php', 3],
            ['Epe & Ibeju-Lekki', 'Lagos Emerging', 'https://images.unsplash.com/photo-1600607687644-c7171b42498f?w=800&auto=format&fit=crop&q=80', 'Growth corridors and new-town developments for long-term investors and custom home builds.', 'map-outline', 'Land & estates', 'analytics-outline', 'Growth corridor', 'property.php', 4],
            ['Surulere & Yaba', 'Lagos Urban', 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800&auto=format&fit=crop&q=80', 'Urban infill, mixed-use upgrades, and residential retrofits in established Lagos neighbourhoods.', 'business-outline', 'Urban renewal', 'construct-outline', 'Retrofit builds', 'property.php', 5],
            ['Banana Island & Osborne', 'Lagos Waterfront', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&auto=format&fit=crop&q=80', 'Ultra-premium waterfront homes and bespoke architectural residences on Lagos Island.', 'star-outline', 'Waterfront', 'people-outline', 'Bespoke homes', 'property.php', 6],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO service_areas
             (title, tag, image_url, description, meta1_icon, meta1_text, meta2_icon, meta2_text, link_url, sort_order, is_published)
             VALUES
             (:title, :tag, :image_url, :description, :meta1_icon, :meta1_text, :meta2_icon, :meta2_text, :link_url, :sort_order, 1)'
        );

        foreach ($areas as [$title, $tag, $image, $desc, $i1, $t1, $i2, $t2, $link, $order]) {
            $stmt->execute([
                'title'       => $title,
                'tag'         => $tag,
                'image_url'   => $image,
                'description' => $desc,
                'meta1_icon'  => $i1,
                'meta1_text'  => $t1,
                'meta2_icon'  => $i2,
                'meta2_text'  => $t2,
                'link_url'    => $link,
                'sort_order'  => $order,
            ]);
        }
        echo 'Seeded Lagos-only service areas (' . count($areas) . " rows).\n";
    }

    $testimonialUpdates = $pdo->exec(
        "UPDATE testimonials SET message = REPLACE(message, ' in Lagos or Abuja', ' in Lagos')
         WHERE LOWER(message) LIKE '%abuja%'"
    );
    if ($testimonialUpdates > 0) {
        echo "Updated {$testimonialUpdates} testimonial(s) to Lagos-only copy.\n";
    }

    foreach (['chatbot_faqs', 'chatbot_knowledgebase', 'chatbot_responses'] as $table) {
        $exists = (int) $pdo->query(
            "SELECT COUNT(*) FROM information_schema.tables
             WHERE table_schema = DATABASE() AND table_name = " . $pdo->quote($table)
        )->fetchColumn();
        if ($exists === 0) {
            continue;
        }
        $cols = $pdo->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(PDO::FETCH_COLUMN);
        $textCols = array_values(array_intersect($cols, ['answer', 'content', 'response_text', 'question', 'title', 'keywords']));
        foreach ($textCols as $col) {
            $pdo->exec(
                "UPDATE `{$table}` SET `{$col}` = REPLACE(`{$col}`, 'Lagos and Abuja', 'Lagos')
                 WHERE LOWER(`{$col}`) LIKE '%abuja%'"
            );
            $pdo->exec(
                "UPDATE `{$table}` SET `{$col}` = REPLACE(`{$col}`, 'Lagos & Abuja', 'Lagos')
                 WHERE LOWER(`{$col}`) LIKE '%abuja%'"
            );
            $pdo->exec(
                "UPDATE `{$table}` SET `{$col}` = REPLACE(`{$col}`, 'Lagos or Abuja', 'Lagos')
                 WHERE LOWER(`{$col}`) LIKE '%abuja%'"
            );
        }
        if (in_array('title', $cols, true)) {
            $pdo->exec("DELETE FROM `{$table}` WHERE LOWER(title) LIKE '%abuja%'");
        }
        if (in_array('question', $cols, true)) {
            $pdo->exec("DELETE FROM `{$table}` WHERE LOWER(question) LIKE '%abuja%'");
        }
        echo "Scrubbed Abuja references from {$table}.\n";
    }

    $homepagePath = dirname(__DIR__) . '/config/homepage-content.php';
    if (is_file($homepagePath)) {
        $raw = (string) file_get_contents($homepagePath);
        $patched = str_replace(
            ['Lagos, Abuja, and beyond', 'Projects Across Lagos & Abuja', 'Lagos and Abuja'],
            ['Lagos and beyond', 'Projects Across Lagos', 'Lagos'],
            $raw
        );
        if ($patched !== $raw) {
            file_put_contents($homepagePath, $patched);
            echo "Updated config/homepage-content.php (Lagos only).\n";
        }
    }

    echo "Done.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Upgrade failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
