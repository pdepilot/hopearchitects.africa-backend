<?php
declare(strict_types=1);

final class HomepageContentService
{
    private const CONFIG_FILE = 'config/homepage-content.php';

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'slides' => [
                [
                    'eyebrow' => 'Architecture & Construction',
                    'title'   => 'Hope <span class="accent">Architects</span>',
                    'tagline' => 'World-class architectural design and construction — shaping iconic spaces with precision, innovation, and enduring craftsmanship.',
                    'bgImage' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=1920&auto=format&fit=crop&q=80',
                ],
                [
                    'eyebrow' => 'Visionary Design',
                    'title'   => 'Designing the <span class="accent">Future</span> Built',
                    'tagline' => 'From concept sketches to master plans — we translate bold ideas into refined, functional architecture that stands the test of time.',
                    'bgImage' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&auto=format&fit=crop&q=80',
                ],
                [
                    'eyebrow' => 'Precision Construction',
                    'title'   => 'Built with <span class="accent">Excellence</span>',
                    'tagline' => 'Our construction teams deliver on schedule and on spec — residential towers, commercial complexes, and bespoke estates across Nigeria.',
                    'bgImage' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=1920&auto=format&fit=crop&q=80',
                ],
                [
                    'eyebrow' => 'End-to-End Delivery',
                    'title'   => 'From Blueprint to <span class="accent">Reality</span>',
                    'tagline' => 'Full project lifecycle management — feasibility, design, approvals, construction, and handover with transparent communication at every stage.',
                    'bgImage' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920&auto=format&fit=crop&q=80',
                ],
                [
                    'eyebrow' => 'Trusted Since 2015',
                    'title'   => 'Nigeria\'s <span class="accent">Finest</span> Builds',
                    'tagline' => 'Over a decade of landmark projects in Lagos and beyond — trusted by developers, investors, and families who demand world-class results.',
                    'bgImage' => 'https://images.unsplash.com/photo-1590645726608-2fd54776569d?w=1920&auto=format&fit=crop&q=80',
                ],
            ],
            'stats' => [
                ['icon' => 'business-outline', 'num' => '120+', 'label' => 'Projects Delivered'],
                ['icon' => 'people-outline', 'num' => '850+', 'label' => 'Satisfied Clients'],
                ['icon' => 'star-outline', 'num' => '5★', 'label' => 'Design Rating'],
                ['icon' => 'ribbon-outline', 'num' => '10+', 'label' => 'Years of Excellence'],
            ],
            'sections' => [
                'about' => [
                    'subtitle' => 'About Us',
                    'title'    => 'World-Class Architecture & Construction.',
                    'text'     => 'Hope Architects combines creative design intelligence with rigorous construction management — delivering buildings that are beautiful, functional, and built to last.',
                ],
                'services' => [
                    'subtitle' => 'Our Expertise',
                    'title'    => 'What We Build',
                ],
                'featured' => [
                    'subtitle' => 'Featured Projects',
                    'title'    => 'Signature Works',
                ],
                'whyUs' => [
                    'title' => 'We Turn Vision Into<br><em>Landmark Architecture</em>',
                    'text'  => 'With over a decade of experience across Nigeria, Hope Architects stands apart through design excellence, structural integrity, and a relentless commitment to delivering on promise.',
                ],
                'process' => [
                    'subtitle' => 'Our Process',
                    'title'    => 'Your Project in 4 Clear Phases',
                ],
                'testimonials' => [
                    'subtitle' => 'Client Stories',
                    'title'    => 'What Our Partners Say',
                ],
                'areas' => [
                    'subtitle' => 'Where We Build',
                    'title'    => 'Projects Across Lagos',
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
        $path = self::configPath();
        if (!is_file($path)) {
            return self::defaults();
        }

        $config = require $path;
        if (!is_array($config)) {
            return self::defaults();
        }

        $merged = array_merge(self::defaults(), $config);
        if (!isset($config['slides']) || !is_array($config['slides'])) {
            $merged['slides'] = self::defaults()['slides'];
        }
        if (!isset($config['stats']) || !is_array($config['stats'])) {
            $merged['stats'] = self::defaults()['stats'];
        }
        if (!isset($config['sections']) || !is_array($config['sections'])) {
            $merged['sections'] = self::defaults()['sections'];
        } else {
            $merged['sections'] = array_replace_recursive(self::defaults()['sections'], $config['sections']);
        }

        return $merged;
    }

    /** @param array<string, mixed> $input */
    public static function save(array $input): bool
    {
        $current = self::get();
        $config = [
            'slides'    => self::sanitizeSlides($input['slides'] ?? $current['slides']),
            'stats'     => self::sanitizeStats($input['stats'] ?? $current['stats']),
            'sections'  => self::sanitizeSections($input['sections'] ?? $current['sections']),
            'updatedAt' => date('c'),
        ];

        $php = "<?php\n"
            . "declare(strict_types=1);\n\n"
            . "/** Auto-generated by Admin → Homepage Content */\n"
            . 'return ' . var_export($config, true) . ";\n";

        return file_put_contents(self::configPath(), $php) !== false;
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
                'bgImage' => self::clip((string) ($slide['bgImage'] ?? ''), 512),
            ];
        }

        return $out !== [] ? $out : self::defaults()['slides'];
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

    private static function clip(string $value, int $max): string
    {
        $value = trim($value);
        return strlen($value) <= $max ? $value : substr($value, 0, $max);
    }
}
