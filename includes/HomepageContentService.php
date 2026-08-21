<?php
declare(strict_types=1);

final class HomepageContentService
{
    private const CONFIG_FILE = 'config/homepage-content.php';

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'preloader' => [
                'enabled'   => true,
                'video'     => '',
                'ariaLabel' => 'Loading Hope Architects',
            ],
            'hero' => [
                'location'        => 'Lagos, Nigeria',
                'primaryCtaLabel' => 'Explore Projects',
                'primaryCtaHref'  => 'property',
                'secondaryCtaLabel' => 'Request Consultation',
                'secondaryCtaHref'  => 'contact',
            ],
            'slides' => [
                [
                    'eyebrow' => 'Architecture & Interior Design',
                    'title'   => 'Hope <span class="accent">Architects</span>',
                    'tagline' => 'World-class architectural design and interior spaces — shaping iconic environments with precision, innovation, and enduring craftsmanship.',
                    'bgImage' => 'assets/images/IMG-20260720-WA0006.jpg',
                ],
                [
                    'eyebrow' => 'Visionary Interiors',
                    'title'   => 'Designing the <span class="accent">Future</span> Lived',
                    'tagline' => 'From concept sketches to finished rooms — we translate bold ideas into refined, functional interiors that stand the test of time.',
                    'bgImage' => 'assets/images/IMG-20260720-WA0005.jpg',
                ],
                [
                    'eyebrow' => 'Precision Craft',
                    'title'   => 'Built with <span class="accent">Excellence</span>',
                    'tagline' => 'Kitchens, living spaces, and bespoke residences delivered on schedule and on spec — with materials and detailing that feel intentional.',
                    'bgImage' => 'assets/images/IMG-20260720-WA0007.jpg',
                ],
                [
                    'eyebrow' => 'End-to-End Delivery',
                    'title'   => 'From Blueprint to <span class="accent">Reality</span>',
                    'tagline' => 'Full project lifecycle management — feasibility, design, approvals, construction, and handover with transparent communication at every stage.',
                    'bgImage' => 'assets/images/IMG-20260720-WA0008.jpg',
                ],
                [
                    'eyebrow' => 'Trusted Since 2015',
                    'title'   => 'Nigeria\'s <span class="accent">Finest</span> Spaces',
                    'tagline' => 'Over a decade of landmark interiors and architecture in Lagos and beyond — trusted by clients who demand world-class results.',
                    'bgImage' => 'assets/images/IMG-20260720-WA0009.jpg',
                ],
            ],
            'stats' => [
                ['icon' => 'business-outline', 'num' => '120+', 'label' => 'Projects Delivered'],
                ['icon' => 'people-outline', 'num' => '850+', 'label' => 'Satisfied Clients'],
                ['icon' => 'star-outline', 'num' => '5★', 'label' => 'Design Rating'],
                ['icon' => 'ribbon-outline', 'num' => '10+', 'label' => 'Years of Excellence'],
            ],
            'about' => [
                'subtitle'   => 'About Us',
                'title'      => 'World-Class Architecture & Construction.',
                'text'       => 'Hope Architects combines creative design intelligence with rigorous construction management — delivering buildings that are beautiful, functional, and built to last.',
                'quote'      => 'Where vision meets precision — turning ambitious concepts into landmark buildings within your scope and budget.',
                'ctaLabel'   => 'Meet Our Team',
                'imageMain'  => 'assets/images/IMG-20260720-WA0010.jpg',
                'imageFloat' => 'assets/images/IMG-20260720-WA0011.jpg',
                'ringNum'    => '15+',
                'ringLabel'  => 'Years Building',
                'pillars'    => [
                    ['icon' => 'color-palette-outline', 'label' => 'Design & Master Planning'],
                    ['icon' => 'construct-outline', 'label' => 'Construction Management'],
                    ['icon' => 'layers-outline', 'label' => 'Structural Engineering'],
                    ['icon' => 'shield-checkmark-outline', 'label' => 'Quality Delivery'],
                ],
                'metrics' => [
                    ['num' => '200+', 'label' => 'Projects'],
                    ['num' => '50+', 'label' => 'Experts'],
                    ['num' => '100%', 'label' => 'Commitment'],
                ],
            ],
            'whyUs' => [
                'subtitle'  => 'Why Choose Us',
                'title'     => 'We Turn Vision Into<br><em>Landmark Architecture</em>',
                'text'      => 'With over a decade of experience across Nigeria, Hope Architects stands apart through design excellence, structural integrity, and a relentless commitment to delivering on promise.',
                'image'     => 'assets/images/IMG-20260720-WA0008.jpg',
                'badgeNum'  => '10+',
                'badgeTxt'  => 'Years of Excellence',
                'ctaLabel'  => 'Learn More About Us',
                'items'     => [
                    ['icon' => 'shield-checkmark-outline', 'title' => 'Structural Integrity', 'text' => 'Every build meets rigorous engineering and safety standards.'],
                    ['icon' => 'ribbon-outline', 'title' => 'Award-Winning Design', 'text' => 'Recognized excellence in architecture and construction.'],
                    ['icon' => 'people-outline', 'title' => 'Expert Team', 'text' => 'Architects, engineers, and builders under one roof.'],
                    ['icon' => 'headset-outline', 'title' => 'Dedicated Support', 'text' => 'Transparent communication from concept to handover.'],
                ],
            ],
            'process' => [
                'subtitle' => 'Our Process',
                'title'    => 'Your Project in 4 Clear Phases',
                'steps'    => [
                    ['icon' => 'chatbubbles-outline', 'title' => 'Consultation', 'text' => 'We listen to your vision, assess the site, and define scope, budget, and timeline.'],
                    ['icon' => 'color-palette-outline', 'title' => 'Design', 'text' => 'Concept sketches evolve into detailed architectural plans and 3D visualizations.'],
                    ['icon' => 'document-text-outline', 'title' => 'Approvals', 'text' => 'We coordinate permits, structural reviews, and regulatory compliance.'],
                    ['icon' => 'construct-outline', 'title' => 'Build & Handover', 'text' => 'Construction managed to the highest standard — delivered on time, ready to occupy.'],
                ],
            ],
            'deliver' => [
                'subtitle'  => 'What We Deliver',
                'title'     => 'Built for<br>Every Scale',
                'lead'      => 'From intimate renovations to landmark developments — full-spectrum architecture and construction under one roof.',
                'ctaLabel'  => 'Explore All Services',
                'statNum'   => '8+',
                'statLabel' => 'Core disciplines',
                'tiles'     => [
                    ['icon' => 'home-outline', 'title' => 'Residential', 'text' => 'Custom homes and estates designed for how you live.'],
                    ['icon' => 'business-outline', 'title' => 'Commercial', 'text' => 'Offices and mixed-use spaces that inspire teams.'],
                    ['icon' => 'leaf-outline', 'title' => 'Sustainable Design', 'text' => 'Green building and energy-efficient materials.'],
                    ['icon' => 'cube-outline', 'title' => '3D Visualization', 'text' => 'Photorealistic renders before ground is broken.'],
                    ['icon' => 'git-network-outline', 'title' => 'Structural Engineering', 'text' => 'Safe, durable, code-compliant structures.'],
                    ['icon' => 'map-outline', 'title' => 'Site Planning', 'text' => 'Land use, zoning, and master plan development.'],
                    ['icon' => 'hammer-outline', 'title' => 'Renovation', 'text' => 'Transform existing structures with modern upgrades.'],
                    ['icon' => 'call-outline', 'title' => 'Start a Project', 'text' => 'Let\'s discuss your vision — book a consultation today.', 'isCta' => true],
                ],
            ],
            'sections' => [
                'services' => [
                    'subtitle' => 'Our Expertise',
                    'title'    => 'What We Build',
                ],
                'featured' => [
                    'subtitle' => 'Featured Projects',
                    'title'    => 'Signature Works',
                ],
                'testimonials' => [
                    'subtitle' => 'Testimonials',
                    'title'    => 'What Our Clients Say',
                ],
                'areas' => [
                    'subtitle' => 'Local Expertise',
                    'title'    => 'Building Across<br><em>Lagos</em>',
                ],
            ],
            'updatedAt' => null,
        ];
    }

    public static function configPath(): string
    {
        return dirname(__DIR__) . '/' . self::CONFIG_FILE;
    }

    /** @return array<string, mixed> */
    public static function get(): array
    {
        $defaults = self::defaults();
        $path = self::configPath();
        if (!is_file($path)) {
            return $defaults;
        }

        try {
            $config = require $path;
        } catch (Throwable $e) {
            return $defaults;
        }

        if (!is_array($config)) {
            return $defaults;
        }

        // Migrate legacy section keys into structured blocks.
        $legacySections = is_array($config['sections'] ?? null) ? $config['sections'] : [];
        if (!isset($config['about']) && isset($legacySections['about'])) {
            $config['about'] = array_merge($defaults['about'], $legacySections['about']);
        }
        if (!isset($config['whyUs']) && isset($legacySections['whyUs'])) {
            $config['whyUs'] = array_merge($defaults['whyUs'], $legacySections['whyUs']);
        }
        if (!isset($config['process']) && isset($legacySections['process'])) {
            $config['process'] = array_merge($defaults['process'], $legacySections['process']);
        }

        $merged = array_replace_recursive($defaults, $config);

        foreach (['slides', 'stats', 'preloader', 'hero', 'about', 'whyUs', 'process', 'deliver', 'sections'] as $key) {
            if (!isset($config[$key]) || !is_array($config[$key])) {
                $merged[$key] = $defaults[$key];
            }
        }

        // Keep heading-only sections merged with defaults for missing keys.
        if (isset($config['sections']) && is_array($config['sections'])) {
            $merged['sections'] = array_replace_recursive($defaults['sections'], $config['sections']);
            // Drop migrated keys if still present
            unset($merged['sections']['about'], $merged['sections']['whyUs'], $merged['sections']['process']);
        }

        return $merged;
    }

    /** @param array<string, mixed> $input */
    public static function save(array $input): bool
    {
        $current = self::get();
        $config = [
            'preloader' => self::sanitizePreloader(array_key_exists('preloader', $input) ? $input['preloader'] : $current['preloader']),
            'hero'      => self::sanitizeHero(array_key_exists('hero', $input) ? $input['hero'] : $current['hero']),
            'slides'    => self::sanitizeSlides(array_key_exists('slides', $input) ? $input['slides'] : $current['slides']),
            'stats'     => self::sanitizeStats(array_key_exists('stats', $input) ? $input['stats'] : $current['stats']),
            'about'     => self::sanitizeAbout(array_key_exists('about', $input) ? $input['about'] : $current['about']),
            'whyUs'     => self::sanitizeWhyUs(array_key_exists('whyUs', $input) ? $input['whyUs'] : $current['whyUs']),
            'process'   => self::sanitizeProcess(array_key_exists('process', $input) ? $input['process'] : $current['process']),
            'deliver'   => self::sanitizeDeliver(array_key_exists('deliver', $input) ? $input['deliver'] : $current['deliver']),
            'sections'  => self::sanitizeSections(array_key_exists('sections', $input) ? $input['sections'] : $current['sections']),
            'updatedAt' => date('c'),
        ];

        // Keep omitted sections from current when caller passes null explicitly.
        foreach (['preloader', 'hero', 'slides', 'stats', 'about', 'whyUs', 'process', 'deliver', 'sections'] as $key) {
            if (array_key_exists($key, $input) && $input[$key] === null) {
                $config[$key] = $current[$key];
            }
        }

        $php = "<?php\n"
            . "declare(strict_types=1);\n\n"
            . "/** Auto-generated by Admin → Homepage Content */\n"
            . 'return ' . var_export($config, true) . ";\n";

        $path = self::configPath();
        $dir = dirname($path);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return false;
        }

        $tmp = $path . '.tmp.' . bin2hex(random_bytes(4));
        if (file_put_contents($tmp, $php, LOCK_EX) === false) {
            return false;
        }

        if (!rename($tmp, $path)) {
            @unlink($tmp);
            return file_put_contents($path, $php, LOCK_EX) !== false;
        }

        return true;
    }

    /** @param mixed $value @return array<string, mixed> */
    private static function sanitizePreloader(mixed $value): array
    {
        $d = self::defaults()['preloader'];
        if (!is_array($value)) {
            return $d;
        }

        return [
            'enabled'   => !empty($value['enabled']) && $value['enabled'] !== '0' && $value['enabled'] !== false,
            'video'     => self::clipPath((string) ($value['video'] ?? $d['video'])),
            'ariaLabel' => self::clip((string) ($value['ariaLabel'] ?? $d['ariaLabel']), 120),
        ];
    }

    /** @param mixed $value @return array<string, string> */
    private static function sanitizeHero(mixed $value): array
    {
        $d = self::defaults()['hero'];
        if (!is_array($value)) {
            return $d;
        }

        return [
            'location'          => self::clip((string) ($value['location'] ?? $d['location']), 80),
            'primaryCtaLabel'   => self::clip((string) ($value['primaryCtaLabel'] ?? $d['primaryCtaLabel']), 60),
            'primaryCtaHref'    => self::clip((string) ($value['primaryCtaHref'] ?? $d['primaryCtaHref']), 80),
            'secondaryCtaLabel' => self::clip((string) ($value['secondaryCtaLabel'] ?? $d['secondaryCtaLabel']), 60),
            'secondaryCtaHref'  => self::clip((string) ($value['secondaryCtaHref'] ?? $d['secondaryCtaHref']), 80),
        ];
    }

    /** @param mixed $slides @return list<array<string, string>> */
    private static function sanitizeSlides(mixed $slides): array
    {
        if (!is_array($slides)) {
            return self::defaults()['slides'];
        }

        $out = [];
        foreach ($slides as $slide) {
            if (!is_array($slide)) {
                continue;
            }
            $out[] = [
                'eyebrow' => self::clip((string) ($slide['eyebrow'] ?? ''), 120),
                'title'   => self::clip((string) ($slide['title'] ?? ''), 300),
                'tagline' => self::clip((string) ($slide['tagline'] ?? ''), 500),
                'bgImage' => self::clipPath((string) ($slide['bgImage'] ?? '')),
            ];
        }

        return $out !== [] ? array_slice($out, 0, 10) : self::defaults()['slides'];
    }

    /** @param mixed $stats @return list<array<string, string>> */
    private static function sanitizeStats(mixed $stats): array
    {
        if (!is_array($stats)) {
            return self::defaults()['stats'];
        }

        $out = [];
        foreach ($stats as $stat) {
            if (!is_array($stat)) {
                continue;
            }
            $out[] = [
                'icon'  => self::clip((string) ($stat['icon'] ?? 'home-outline'), 40),
                'num'   => self::clip((string) ($stat['num'] ?? ''), 20),
                'label' => self::clip((string) ($stat['label'] ?? ''), 80),
            ];
        }

        return $out !== [] ? array_slice($out, 0, 6) : self::defaults()['stats'];
    }

    /** @param mixed $value @return array<string, mixed> */
    private static function sanitizeAbout(mixed $value): array
    {
        $d = self::defaults()['about'];
        if (!is_array($value)) {
            return $d;
        }

        $pillars = [];
        foreach ((array) ($value['pillars'] ?? $d['pillars']) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $pillars[] = [
                'icon'  => self::clip((string) ($item['icon'] ?? 'home-outline'), 40),
                'label' => self::clip((string) ($item['label'] ?? ''), 80),
            ];
        }

        $metrics = [];
        foreach ((array) ($value['metrics'] ?? $d['metrics']) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $metrics[] = [
                'num'   => self::clip((string) ($item['num'] ?? ''), 20),
                'label' => self::clip((string) ($item['label'] ?? ''), 40),
            ];
        }

        return [
            'subtitle'   => self::clip((string) ($value['subtitle'] ?? $d['subtitle']), 120),
            'title'      => self::clip((string) ($value['title'] ?? $d['title']), 300),
            'text'       => self::clip((string) ($value['text'] ?? $d['text']), 1200),
            'quote'      => self::clip((string) ($value['quote'] ?? $d['quote']), 400),
            'ctaLabel'   => self::clip((string) ($value['ctaLabel'] ?? $d['ctaLabel']), 60),
            'imageMain'  => self::clipPath((string) ($value['imageMain'] ?? $d['imageMain'])),
            'imageFloat' => self::clipPath((string) ($value['imageFloat'] ?? $d['imageFloat'])),
            'ringNum'    => self::clip((string) ($value['ringNum'] ?? $d['ringNum']), 20),
            'ringLabel'  => self::clip((string) ($value['ringLabel'] ?? $d['ringLabel']), 40),
            'pillars'    => $pillars !== [] ? array_slice($pillars, 0, 6) : $d['pillars'],
            'metrics'    => $metrics !== [] ? array_slice($metrics, 0, 4) : $d['metrics'],
        ];
    }

    /** @param mixed $value @return array<string, mixed> */
    private static function sanitizeWhyUs(mixed $value): array
    {
        $d = self::defaults()['whyUs'];
        if (!is_array($value)) {
            return $d;
        }

        $items = [];
        foreach ((array) ($value['items'] ?? $d['items']) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $items[] = [
                'icon'  => self::clip((string) ($item['icon'] ?? 'home-outline'), 40),
                'title' => self::clip((string) ($item['title'] ?? ''), 80),
                'text'  => self::clip((string) ($item['text'] ?? ''), 240),
            ];
        }

        return [
            'subtitle' => self::clip((string) ($value['subtitle'] ?? $d['subtitle']), 120),
            'title'    => self::clip((string) ($value['title'] ?? $d['title']), 400),
            'text'     => self::clip((string) ($value['text'] ?? $d['text']), 1200),
            'image'    => self::clipPath((string) ($value['image'] ?? $d['image'])),
            'badgeNum' => self::clip((string) ($value['badgeNum'] ?? $d['badgeNum']), 20),
            'badgeTxt' => self::clip((string) ($value['badgeTxt'] ?? $d['badgeTxt']), 60),
            'ctaLabel' => self::clip((string) ($value['ctaLabel'] ?? $d['ctaLabel']), 60),
            'items'    => $items !== [] ? array_slice($items, 0, 8) : $d['items'],
        ];
    }

    /** @param mixed $value @return array<string, mixed> */
    private static function sanitizeProcess(mixed $value): array
    {
        $d = self::defaults()['process'];
        if (!is_array($value)) {
            return $d;
        }

        $steps = [];
        foreach ((array) ($value['steps'] ?? $d['steps']) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $steps[] = [
                'icon'  => self::clip((string) ($item['icon'] ?? 'home-outline'), 40),
                'title' => self::clip((string) ($item['title'] ?? ''), 80),
                'text'  => self::clip((string) ($item['text'] ?? ''), 300),
            ];
        }

        return [
            'subtitle' => self::clip((string) ($value['subtitle'] ?? $d['subtitle']), 120),
            'title'    => self::clip((string) ($value['title'] ?? $d['title']), 200),
            'steps'    => $steps !== [] ? array_slice($steps, 0, 6) : $d['steps'],
        ];
    }

    /** @param mixed $value @return array<string, mixed> */
    private static function sanitizeDeliver(mixed $value): array
    {
        $d = self::defaults()['deliver'];
        if (!is_array($value)) {
            return $d;
        }

        $tiles = [];
        foreach ((array) ($value['tiles'] ?? $d['tiles']) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tiles[] = [
                'icon'  => self::clip((string) ($item['icon'] ?? 'home-outline'), 40),
                'title' => self::clip((string) ($item['title'] ?? ''), 80),
                'text'  => self::clip((string) ($item['text'] ?? ''), 240),
                'isCta' => !empty($item['isCta']),
            ];
        }

        return [
            'subtitle'  => self::clip((string) ($value['subtitle'] ?? $d['subtitle']), 120),
            'title'     => self::clip((string) ($value['title'] ?? $d['title']), 300),
            'lead'      => self::clip((string) ($value['lead'] ?? $d['lead']), 500),
            'ctaLabel'  => self::clip((string) ($value['ctaLabel'] ?? $d['ctaLabel']), 60),
            'statNum'   => self::clip((string) ($value['statNum'] ?? $d['statNum']), 20),
            'statLabel' => self::clip((string) ($value['statLabel'] ?? $d['statLabel']), 60),
            'tiles'     => $tiles !== [] ? array_slice($tiles, 0, 12) : $d['tiles'],
        ];
    }

    /** @param mixed $sections @return array<string, array<string, string>> */
    private static function sanitizeSections(mixed $sections): array
    {
        $defaults = self::defaults()['sections'];
        if (!is_array($sections)) {
            return $defaults;
        }

        $out = $defaults;
        foreach ($defaults as $key => $fields) {
            if (!isset($sections[$key]) || !is_array($sections[$key])) {
                continue;
            }
            foreach ($fields as $field => $defaultVal) {
                $out[$key][$field] = self::clip((string) ($sections[$key][$field] ?? $defaultVal), 1000);
            }
        }

        return $out;
    }

    private static function clipPath(string $value): string
    {
        $value = trim(str_replace('\\', '/', $value));
        if ($value === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $value)) {
            return '';
        }
        $value = ltrim($value, '/');
        $value = preg_replace('#^\./+#', '', $value) ?? $value;
        if (str_contains($value, '..')) {
            return '';
        }
        if (!preg_match('#^(assets/(images|uploads|videos)/)#i', $value)) {
            return self::clip($value, 512);
        }

        return self::clip($value, 512);
    }

    private static function clip(string $value, int $max): string
    {
        $value = trim($value);

        return strlen($value) <= $max ? $value : substr($value, 0, $max);
    }
}
