<?php
/**
 * Reposition portfolio for architecture/interior design:
 * - zero out listing prices
 * - replace remote placeholder images with local assets/images files
 * - seed featured projects when the portfolio is empty
 *
 * Run once: php sql/upgrade_portfolio_design.php
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/portfolio_helpers.php';

try {
    $pdo = getDatabaseConnection();
    $catalog = portfolioImageCatalog();

    $zeroed = $pdo->exec('UPDATE properties SET price = 0 WHERE price <> 0');
    echo "Set price = 0 on {$zeroed} project row(s).\n";

    $rows = $pdo->query('SELECT id, image_url FROM properties ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
    $update = $pdo->prepare('UPDATE properties SET image_url = :image_url WHERE id = :id');
    $replaced = 0;

    foreach ($rows as $i => $row) {
        $current = (string) ($row['image_url'] ?? '');
        $needsReplace = $current === ''
            || str_contains(strtolower($current), 'unsplash.com')
            || str_contains(strtolower($current), 'placehold.co');

        if (!$needsReplace) {
            continue;
        }

        $local = portfolioImageAt((int) $i);
        $update->execute(['image_url' => $local, 'id' => (int) $row['id']]);
        $replaced++;
    }
    echo "Updated {$replaced} project image(s) to local assets.\n";

    $count = (int) $pdo->query('SELECT COUNT(*) FROM properties WHERE approval_status = "approved"')->fetchColumn();
    if ($count < 8 && $catalog !== []) {
        $samples = [
            ['Contemporary Lagos Residence', 'sale', 'Victoria Island, Lagos', 'Full architectural design and build for a contemporary family residence with open-plan living and premium finishes.'],
            ['Lekki Penthouse Interior', 'rent', 'Lekki Phase 1, Lagos', 'Bespoke interior design package — custom joinery, lighting design, and curated furnishings for a luxury penthouse.'],
            ['Commercial Plaza Facade', 'sale', 'Ikeja GRA, Lagos', 'Architectural concept and construction documentation for a mixed-use commercial plaza with landmark street presence.'],
            ['Minimalist Office Fit-out', 'rent', 'Yaba, Lagos', 'Interior design and space planning for a technology company headquarters with collaborative zones and executive suites.'],
            ['Waterfront Villa Concept', 'sale', 'Banana Island, Lagos', 'Concept-to-schematic design for an ultra-premium waterfront villa with panoramic lagoon views.'],
            ['Boutique Hotel Lobby', 'rent', 'Ikoyi, Lagos', 'Interior architecture for a boutique hotel lobby — material palette, lighting, and guest journey design.'],
            ['Duplex Renovation', 'rent', 'Surulere, Lagos', 'Interior renovation and spatial reconfiguration for a heritage duplex with modern amenities.'],
            ['Estate Master Planning', 'sale', 'Epe, Lagos', 'Master planning and architectural guidelines for a gated residential estate development.'],
            ['Restaurant Interior Concept', 'rent', 'Lekki, Lagos', 'Interior design concept for a fine-dining restaurant with theatrical lighting and custom seating.'],
            ['Smart Home Architecture', 'sale', 'Ajah, Lagos', 'Architectural design for a smart-enabled family home with sustainable systems integration.'],
            ['Showroom Interior', 'rent', 'Victoria Island, Lagos', 'Retail interior design for a luxury automotive showroom with immersive brand experience.'],
            ['Institutional Campus Block', 'sale', 'Mainland, Lagos', 'Institutional architecture for a multi-block educational campus with phased delivery.'],
        ];

        $insert = $pdo->prepare(
            'INSERT INTO properties
             (title, price, type, location, bedrooms, bathrooms, area, approval_status, source, image_url, description)
             VALUES
             (:title, 0, :type, :location, :bedrooms, :bathrooms, :area, "approved", "admin", :image_url, :description)'
        );

        $seeded = 0;
        foreach ($samples as $i => [$title, $type, $location, $description]) {
            if ($count + $seeded >= 12) {
                break;
            }
            $insert->execute([
                'title'       => $title,
                'type'        => $type,
                'location'    => $location,
                'bedrooms'    => 3 + ($i % 3),
                'bathrooms'   => 2 + ($i % 2),
                'area'        => 180 + ($i * 25),
                'image_url'   => portfolioImageAt($i),
                'description' => $description,
            ]);
            $seeded++;
        }
        echo "Seeded {$seeded} portfolio project(s).\n";
    }

    if ($pdo->query("SHOW TABLES LIKE 'service_areas'")->fetchColumn()) {
        $areas = $pdo->query('SELECT id, image_url FROM service_areas ORDER BY sort_order ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
        $areaUpdate = $pdo->prepare('UPDATE service_areas SET image_url = :image_url WHERE id = :id');
        $areaReplaced = 0;
        foreach ($areas as $i => $area) {
            $current = (string) ($area['image_url'] ?? '');
            if ($current === '' || str_contains(strtolower($current), 'unsplash.com')) {
                $areaUpdate->execute([
                    'image_url' => portfolioImageAt($i + 20),
                    'id'        => (int) $area['id'],
                ]);
                $areaReplaced++;
            }
        }
        if ($areaReplaced > 0) {
            echo "Updated {$areaReplaced} service area image(s) to local assets.\n";
        }
    }

    echo "Portfolio design upgrade complete.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Upgrade failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
