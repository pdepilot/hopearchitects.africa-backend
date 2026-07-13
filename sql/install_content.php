<?php
/**
 * One-time installer: testimonials + service areas tables with seed data.
 * Run: http://localhost/BIVER_ROYAL_ESTATE/sql/install_content.php
 */

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

require_once dirname(__DIR__) . '/config/database.php';

$sqlFile = __DIR__ . '/content_tables.sql';

if (!is_readable($sqlFile)) {
    http_response_code(500);
    echo "content_tables.sql not found.\n";
    exit(1);
}

try {
    $pdo = getDatabaseConnection();
    $pdo->exec(file_get_contents($sqlFile));

    $testimonialCount = (int) $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    if ($testimonialCount === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO testimonials (client_name, message, rating, initials, role_label, sort_order)
             VALUES (:name, :message, :rating, :initials, :role_label, :sort_order)'
        );
        $seedTestimonials = [
            ['Chisom O.', 'Hope Architects made building our dream home in Lekki so seamless. The team was highly professional and transparent.', 5, 'CO', 'Happy Client', 1],
            ['Emeka U.', 'Their property management services are top-notch. I have peace of mind knowing my investments are in good hands.', 5, 'EU', 'Happy Client', 2],
            ['Adaobi N.', 'From start to finish, the construction process was seamless. I highly recommend them to anyone looking for architecture and building services in Lagos.', 5, 'AN', 'Happy Client', 3],
        ];
        foreach ($seedTestimonials as [$name, $message, $rating, $initials, $role, $order]) {
            $stmt->execute([
                'name'       => $name,
                'message'    => $message,
                'rating'     => $rating,
                'initials'   => $initials,
                'role_label' => $role,
                'sort_order' => $order,
            ]);
        }
        echo "Seeded 3 testimonials.\n";
    }

    $areaCount = (int) $pdo->query('SELECT COUNT(*) FROM service_areas')->fetchColumn();
    if ($areaCount === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO service_areas
             (title, tag, image_url, description, meta1_icon, meta1_text, meta2_icon, meta2_text, link_url, sort_order)
             VALUES
             (:title, :tag, :image_url, :description, :meta1_icon, :meta1_text, :meta2_icon, :meta2_text, :link_url, :sort_order)'
        );
        $seedAreas = [
            ['Victoria Island & Ikoyi', 'Lagos Premium', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80', 'Luxury high-rises, waterfront estates, and executive residences in Lagos Island\'s most prestigious addresses.', 'business-outline', 'Premium towers', 'trending-up-outline', 'High demand', 'property.php', 1],
            ['Lekki & Ajah Corridor', 'Lagos Growth', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80', 'Modern estates, smart homes, and mixed-use developments along Lagos\' fastest-growing coastal corridor.', 'home-outline', 'Estates & villas', 'key-outline', 'Rent & sale', 'property.php', 2],
            ['Ikeja GRA & Mainland', 'Lagos Commercial', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=80', 'Commercial plazas, office blocks, and residential towers in Lagos mainland business hubs.', 'storefront-outline', 'Mixed-use', 'shield-checkmark-outline', 'Verified titles', 'property.php', 3],
            ['Epe & Ibeju-Lekki', 'Lagos Emerging', 'https://images.unsplash.com/photo-1600607687644-c7171b42498f?w=800&auto=format&fit=crop&q=80', 'Growth corridors and new-town developments for long-term investors and custom home builds.', 'map-outline', 'Land & estates', 'analytics-outline', 'Growth corridor', 'property.php', 4],
            ['Surulere & Yaba', 'Lagos Urban', 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800&auto=format&fit=crop&q=80', 'Urban infill, mixed-use upgrades, and residential retrofits in established Lagos neighbourhoods.', 'business-outline', 'Urban renewal', 'construct-outline', 'Retrofit builds', 'property.php', 5],
            ['Banana Island & Osborne', 'Lagos Waterfront', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&auto=format&fit=crop&q=80', 'Ultra-premium waterfront homes and bespoke architectural residences on Lagos Island.', 'star-outline', 'Waterfront', 'people-outline', 'Bespoke homes', 'property.php', 6],
        ];
        foreach ($seedAreas as [$title, $tag, $image, $desc, $i1, $t1, $i2, $t2, $link, $order]) {
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
        echo "Seeded 6 service areas.\n";
    }

    echo "Content tables installed successfully.\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "Installation failed: " . $e->getMessage() . "\n";
    exit(1);
}
