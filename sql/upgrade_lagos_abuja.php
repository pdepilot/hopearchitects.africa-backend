<?php
/**
 * Upgrade service areas and section copy to Lagos & Abuja.
 * Run once: php sql/upgrade_lagos_abuja.php
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/ServiceAreaRepository.php';

try {
    $pdo = getDatabaseConnection();

    ServiceAreaRepository::saveSection([
        'intro'    => "From Victoria Island to Maitama — we help clients design and build in Lagos and Abuja's most sought-after districts.",
        'ctaText'  => "Don't see your district? Our teams cover all of Lagos and Abuja.",
        'ctaLink'  => 'contact.php',
        'ctaLabel' => 'Book a Site Consultation',
    ]);
    echo "Updated service areas section copy.\n";

    $areas = [
        ['Victoria Island & Ikoyi', 'Lagos Premium', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80', 'Luxury high-rises, waterfront estates, and executive residences in Lagos Island\'s most prestigious addresses.', 'business-outline', 'Premium towers', 'trending-up-outline', 'High demand', 'property.php', 1],
        ['Lekki & Ajah Corridor', 'Lagos Growth', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80', 'Modern estates, smart homes, and mixed-use developments along Lagos\' fastest-growing coastal corridor.', 'home-outline', 'Estates & villas', 'key-outline', 'Rent & sale', 'property.php', 2],
        ['Ikeja GRA & Mainland', 'Lagos Commercial', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=80', 'Commercial plazas, office blocks, and residential towers in Lagos mainland business hubs.', 'storefront-outline', 'Mixed-use', 'shield-checkmark-outline', 'Verified titles', 'property.php', 3],
        ['Maitama & Asokoro', 'Abuja Executive', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&auto=format&fit=crop&q=80', 'Diplomatic-zone estates and executive mansions in Abuja\'s most exclusive districts.', 'star-outline', 'Premium homes', 'people-outline', 'Family-friendly', 'property.php', 4],
        ['Gwarinpa & Katampe', 'Abuja Family', 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&auto=format&fit=crop&q=80', 'Spacious duplexes and gated communities ideal for families and long-term investors in Abuja.', 'construct-outline', 'New developments', 'analytics-outline', 'Investment potential', 'property.php', 5],
        ['Wuse & Central District', 'Abuja Commercial', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&auto=format&fit=crop&q=80', 'Retail plazas, office complexes, and institutional builds in Abuja\'s central business zone.', 'navigate-outline', 'Central access', 'business-outline', 'Commercial builds', 'property.php', 6],
    ];

    $pdo->exec('DELETE FROM service_areas');
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

    echo "Replaced service areas with Lagos & Abuja districts (" . count($areas) . " rows).\n";
    echo "Done.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Upgrade failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
