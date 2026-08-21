<?php
declare(strict_types=1);

final class PageContentService
{
    private const CONFIG_FILE = 'config/page-content.php';

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'about' => [
                'hero' => [
                    'subtitle'    => 'EST. 2015',
                    'title'       => 'Architects of <span class="gold-accent">Dreams</span>,<br>Builders of Trust',
                    'description' => 'Hope Architects wasn\'t built on transactions — it was built on design integrity. In a world of compromised builds, we chose excellence as our foundation.',
                    'stats'       => [
                        ['num' => '850+', 'label' => 'Clients Served'],
                        ['num' => '120+', 'label' => 'Projects Built'],
                        ['num' => '100%', 'label' => 'Client Trust'],
                        ['num' => '10+', 'label' => 'Years of Excellence'],
                    ],
                ],
                'narrative' => [
                    'badge'     => 'The Untold Story',
                    'title'     => 'From a Bold Vision to Nigeria\'s Most Trusted Architecture Firm',
                    'paragraph1'=> 'In 2015, our founders saw a gap in Nigeria\'s built environment — not a gap in contractors, but a gap in design-led, quality construction.',
                    'paragraph2'=> 'What started as a studio in Lagos has grown into a full-service architecture and construction practice trusted across the city and Nigeria.',
                    'quote'     => 'We don\'t just draw buildings. We craft spaces that elevate how people live and work.',
                    'signature' => 'Mr. Oliva Guiffo, Founder',
                    'mainImage' => 'assets/images/Scene 10_1.png',
                    'floatingImage' => 'assets/images/IMG-20260720-WA0010.jpg',
                    'caption'   => 'The Journey Begins in Lagos, 2015',
                ],
                'journey' => [
                    'eyebrow' => 'Our Journey',
                    'title'   => 'The Road to Redefining Architecture & Construction',
                    'items'   => [
                        ['year' => '2015', 'title' => 'The Seed is Planted', 'text' => 'Hope Architects opens its Lagos studio in Victoria Island. First residential build completed within 3 months.'],
                        ['year' => '2017', 'title' => 'Expansion & Recognition', 'text' => 'Lekki and Mainland studios expand. Team grows to 12 dedicated architects and site engineers across Lagos.'],
                        ['year' => '2019', 'title' => 'Digital Transformation', 'text' => 'Launch of comprehensive project portfolio platform, making our builds accessible to clients across Nigeria.'],
                        ['year' => '2022', 'title' => '100+ Projects Delivered', 'text' => 'Milestone achievement: over 100 residential and commercial projects delivered through Hope Architects.'],
                        ['year' => '2024', 'title' => 'Industry Leadership', 'text' => 'Recognized as a leading architecture and construction firm in Nigeria, setting new standards for integrity and client care.'],
                    ],
                ],
                'values' => [
                    'image' => 'assets/images/Scene 15_1.png',
                    'items' => [
                        ['icon' => 'people-outline', 'title' => 'People First, Always', 'text' => 'Behind every transaction is a family, a dream, a future. We never forget that.'],
                        ['icon' => 'eye-outline', 'title' => 'Radical Transparency', 'text' => 'Every document shared, every fee explained, every process visible. No secrets. Ever.'],
                        ['icon' => 'infinite-outline', 'title' => 'Lifelong Relationships', 'text' => 'We don\'t close doors — we open them. Many clients return for their second, third, and fourth homes.'],
                        ['icon' => 'cube-outline', 'title' => 'Community Builders', 'text' => 'We\'re not just erecting structures; we\'re shaping neighborhoods and strengthening communities.'],
                    ],
                ],
                'team' => [
                    'eyebrow' => 'The Heart Behind the Brand',
                    'title'   => 'Meet the Dream Weavers',
                    'members' => [
                        ['name' => 'Oliva Guiffo', 'role' => 'Founder & CEO', 'image' => 'assets/images/Scene 11_1.png', 'social' => ['facebook' => 'https://facebook.com', 'instagram' => 'https://instagram.com', 'twitter' => 'https://x.com']],
                        ['name' => 'Amara Okafor', 'role' => 'Operations Director', 'image' => 'assets/images/IMG-20260720-WA0011.jpg', 'social' => ['facebook' => '', 'instagram' => 'https://instagram.com', 'twitter' => 'https://x.com']],
                        ['name' => 'Emeka Obi', 'role' => 'Lead Architect', 'image' => 'assets/images/IMG-20260720-WA0014.jpg', 'social' => ['facebook' => 'https://facebook.com', 'instagram' => '', 'twitter' => 'https://x.com']],
                        ['name' => 'Chioma Eze', 'role' => 'Interior Design Lead', 'image' => 'assets/images/IMG-20260720-WA0015.jpg', 'social' => ['facebook' => 'https://facebook.com', 'instagram' => 'https://instagram.com', 'twitter' => '']],
                    ],
                ],
                'cta' => [
                    'title' => 'Ready to Write Your Story With Us?',
                    'text'  => 'Every great build begins with a conversation. Let\'s start yours.',
                    'label' => 'Start Your Journey',
                    'link'  => 'contact',
                ],
            ],
            'services' => [
                'hero' => [
                    'badge'       => 'Our Expertise',
                    'title'       => 'Comprehensive <span class="gold-accent">Architecture</span> &amp; Construction',
                    'description' => 'From master planning to final handover, we deliver world-class design and build services at every stage.',
                    'stats'       => [
                        ['num' => '120+', 'label' => 'Projects Delivered'],
                        ['num' => '850+', 'label' => 'Happy Clients'],
                        ['num' => '98%', 'label' => 'On-Time Delivery'],
                    ],
                ],
                'showcase' => [
                    'eyebrow' => 'What We Offer',
                    'title'   => 'Tailored Services for Every Need',
                ],
                'process' => [
                    'eyebrow' => 'Simple Process',
                    'title'   => 'How We Deliver Excellence',
                ],
                'whyChoose' => [
                    'eyebrow' => 'Why Choose Us',
                    'title'   => 'The Hope Architects Advantage',
                ],
                'cta' => [
                    'title' => 'Ready to Start Your Build?',
                    'text'  => 'Let\'s turn your architectural vision into a world-class reality. Contact our design and construction team today.',
                    'label' => 'Schedule a Consultation',
                    'link'  => 'contact',
                ],
                'cards' => [
                    [
                        'icon' => 'color-palette-outline', 'title' => 'Architectural Design',
                        'description' => 'Concept development, 3D visualization, and detailed architectural drawings for residential and commercial projects.',
                        'features' => ['Master Planning', '3D Renders', 'Building Approvals'],
                        'linkLabel' => 'View Portfolio', 'linkPage' => 'property',
                    ],
                    [
                        'icon' => 'construct-outline', 'title' => 'Construction Management',
                        'description' => 'End-to-end construction with rigorous quality control, timeline management, and transparent reporting.',
                        'features' => ['Site Supervision', 'Quality Assurance', 'Budget Control'],
                        'linkLabel' => 'Our Process', 'linkPage' => 'services',
                    ],
                    [
                        'icon' => 'business-outline', 'title' => 'Commercial Builds',
                        'description' => 'Office towers, retail complexes, and mixed-use developments engineered for performance and prestige.',
                        'features' => ['Structural Engineering', 'MEP Coordination', 'Turnkey Delivery'],
                        'linkLabel' => 'Discuss Project', 'linkPage' => 'contact',
                    ],
                    [
                        'icon' => 'home-outline', 'title' => 'Residential Architecture',
                        'description' => 'Luxury homes, estates, and apartment blocks designed for comfort, sustainability, and lasting value.',
                        'features' => ['Custom Homes', 'Estate Planning', 'Interior Coordination'],
                        'linkLabel' => 'Explore Projects', 'linkPage' => 'property',
                    ],
                    [
                        'icon' => 'layers-outline', 'title' => 'Renovation & Retrofit',
                        'description' => 'Transform existing structures with modern design upgrades and structural improvements.',
                        'features' => ['Structural Assessment', 'Design Refresh', 'Phased Execution'],
                        'linkLabel' => 'Get Consultation', 'linkPage' => 'contact',
                    ],
                    [
                        'icon' => 'map-outline', 'title' => 'Survey & Site Planning',
                        'description' => 'Professional site analysis, survey coordination, and land-use planning for secure project foundations.',
                        'features' => ['Site Surveys', 'Feasibility Studies', 'Land Planning'],
                        'linkLabel' => 'Request Survey', 'linkPage' => 'contact',
                    ],
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

        return array_replace_recursive(self::defaults(), $config);
    }

    /** @return array<string, mixed> */
    public static function getPage(string $page): array
    {
        $all = self::get();
        $page = strtolower(trim($page));

        return is_array($all[$page] ?? null) ? $all[$page] : [];
    }

    /** @param array<string, mixed> $input */
    public static function savePage(string $page, array $input): bool
    {
        $page = strtolower(trim($page));
        if (!in_array($page, ['about', 'services'], true)) {
            throw new InvalidArgumentException('Invalid page slug.');
        }

        $all = self::get();
        if ($page === 'about') {
            $all[$page] = self::sanitizeAbout($input);
        } else {
            $all[$page] = $input;
        }
        $all['updatedAt'] = date('c');

        $php = "<?php\n"
            . "declare(strict_types=1);\n\n"
            . "/** Auto-generated by Admin → Site Pages */\n"
            . 'return ' . var_export($all, true) . ";\n";

        return file_put_contents(self::configPath(), $php) !== false;
    }

    /** @param array<string, mixed> $input @return array<string, mixed> */
    public static function sanitizeAbout(array $input): array
    {
        $defaults = self::defaults()['about'];
        $hero = is_array($input['hero'] ?? null) ? $input['hero'] : [];
        $narrative = is_array($input['narrative'] ?? null) ? $input['narrative'] : [];
        $journey = is_array($input['journey'] ?? null) ? $input['journey'] : [];
        $values = is_array($input['values'] ?? null) ? $input['values'] : [];
        $team = is_array($input['team'] ?? null) ? $input['team'] : [];
        $cta = is_array($input['cta'] ?? null) ? $input['cta'] : [];

        $stats = [];
        if (is_array($hero['stats'] ?? null)) {
            foreach ($hero['stats'] as $stat) {
                if (!is_array($stat)) {
                    continue;
                }
                $num = self::clip((string) ($stat['num'] ?? ''), 20);
                $label = self::clip((string) ($stat['label'] ?? ''), 80);
                if ($num === '' && $label === '') {
                    continue;
                }
                $stats[] = ['num' => $num, 'label' => $label];
            }
        }

        $journeyItems = [];
        if (is_array($journey['items'] ?? null)) {
            foreach ($journey['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $year = self::clip((string) ($item['year'] ?? ''), 12);
                $title = self::clip((string) ($item['title'] ?? ''), 120);
                $text = self::clip((string) ($item['text'] ?? ''), 600);
                if ($year === '' && $title === '' && $text === '') {
                    continue;
                }
                $journeyItems[] = ['year' => $year, 'title' => $title, 'text' => $text];
            }
        }

        $valueItems = [];
        if (is_array($values['items'] ?? null)) {
            foreach ($values['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $icon = self::clip((string) ($item['icon'] ?? 'star-outline'), 40);
                $title = self::clip((string) ($item['title'] ?? ''), 120);
                $text = self::clip((string) ($item['text'] ?? ''), 400);
                if ($title === '' && $text === '') {
                    continue;
                }
                $valueItems[] = ['icon' => $icon, 'title' => $title, 'text' => $text];
            }
        }

        $members = [];
        if (is_array($team['members'] ?? null)) {
            foreach ($team['members'] as $member) {
                if (!is_array($member)) {
                    continue;
                }
                $name = self::clip((string) ($member['name'] ?? ''), 80);
                $role = self::clip((string) ($member['role'] ?? ''), 80);
                $image = self::clip((string) ($member['image'] ?? ''), 512);
                $socialIn = is_array($member['social'] ?? null) ? $member['social'] : [];
                if ($name === '' && $role === '') {
                    continue;
                }
                $members[] = [
                    'name'   => $name,
                    'role'   => $role,
                    'image'  => $image,
                    'social' => self::sanitizeTeamSocial($socialIn),
                ];
            }
        }

        return [
            'hero' => [
                'subtitle'    => self::clip((string) ($hero['subtitle'] ?? $defaults['hero']['subtitle']), 120),
                'title'       => self::clip((string) ($hero['title'] ?? $defaults['hero']['title']), 500),
                'description' => self::clip((string) ($hero['description'] ?? $defaults['hero']['description']), 800),
                'stats'       => $stats !== [] ? array_slice($stats, 0, 6) : $defaults['hero']['stats'],
            ],
            'narrative' => [
                'badge'         => self::clip((string) ($narrative['badge'] ?? $defaults['narrative']['badge']), 80),
                'title'         => self::clip((string) ($narrative['title'] ?? $defaults['narrative']['title']), 200),
                'paragraph1'    => self::clip((string) ($narrative['paragraph1'] ?? $defaults['narrative']['paragraph1']), 1200),
                'paragraph2'    => self::clip((string) ($narrative['paragraph2'] ?? $defaults['narrative']['paragraph2']), 1200),
                'quote'         => self::clip((string) ($narrative['quote'] ?? $defaults['narrative']['quote']), 400),
                'signature'     => self::clip((string) ($narrative['signature'] ?? $defaults['narrative']['signature']), 120),
                'mainImage'     => self::clip((string) ($narrative['mainImage'] ?? $defaults['narrative']['mainImage']), 512),
                'floatingImage' => self::clip((string) ($narrative['floatingImage'] ?? $defaults['narrative']['floatingImage']), 512),
                'caption'       => self::clip((string) ($narrative['caption'] ?? $defaults['narrative']['caption']), 160),
            ],
            'journey' => [
                'eyebrow' => self::clip((string) ($journey['eyebrow'] ?? $defaults['journey']['eyebrow']), 80),
                'title'   => self::clip((string) ($journey['title'] ?? $defaults['journey']['title']), 200),
                'items'   => $journeyItems !== [] ? array_slice($journeyItems, 0, 12) : $defaults['journey']['items'],
            ],
            'values' => [
                'image' => self::clip((string) ($values['image'] ?? $defaults['values']['image']), 512),
                'items' => $valueItems !== [] ? array_slice($valueItems, 0, 8) : $defaults['values']['items'],
            ],
            'team' => [
                'eyebrow' => self::clip((string) ($team['eyebrow'] ?? $defaults['team']['eyebrow']), 80),
                'title'   => self::clip((string) ($team['title'] ?? $defaults['team']['title']), 200),
                'members' => $members !== [] ? array_slice($members, 0, 12) : $defaults['team']['members'],
            ],
            'cta' => [
                'title' => self::clip((string) ($cta['title'] ?? $defaults['cta']['title']), 200),
                'text'  => self::clip((string) ($cta['text'] ?? $defaults['cta']['text']), 400),
                'label' => self::clip((string) ($cta['label'] ?? $defaults['cta']['label']), 80),
                'link'  => self::clip((string) ($cta['link'] ?? $defaults['cta']['link']), 80),
            ],
        ];
    }

    /** @param array<string, mixed> $social @return array<string, string> */
    private static function sanitizeTeamSocial(array $social): array
    {
        $keys = ['facebook', 'instagram', 'twitter'];
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = self::clip((string) ($social[$key] ?? ''), 512);
        }

        return $out;
    }

    private static function clip(string $value, int $max): string
    {
        $value = trim($value);
        return strlen($value) <= $max ? $value : substr($value, 0, $max);
    }
}
