<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@amirkamal.com')],
            ['name' => 'Axis Administrator', 'password' => env('ADMIN_PASSWORD', 'password'), 'is_admin' => true],
        );

        $settings = SiteSetting::query()->firstOrNew(['id' => 1]);
        $settings->fill([
            'email' => 'sales@axisindustrial.com',
            'phone' => '+20 100 000 0000',
            'whatsapp' => '+20 100 000 0000',
            'maintenance_mode' => false,
            'en' => [
                'site_name' => 'Axis Industrial',
                'address' => 'Cairo, Egypt',
                'footer_description' => 'Professional CNC tooling and lathe supplies for modern workshops.',
                'seo_title' => 'Axis Industrial | CNC & Lathe Supplies',
                'seo_description' => 'Professional CNC tooling, lathe supplies, workholding, cutting tools, and metrology equipment.',
            ],
            'ar' => [
                'site_name' => 'أكسس إندستريال',
                'address' => 'القاهرة، مصر',
                'footer_description' => 'أدوات CNC ومستلزمات مخارط احترافية للورش الحديثة.',
                'seo_title' => 'أكسس إندستريال | مستلزمات CNC والمخارط',
                'seo_description' => 'أدوات CNC ومستلزمات مخارط وأدوات تثبيت وقطع وقياس احترافية.',
            ],
        ])->save();

        $categoryData = [
            'Workholding' => ['ar' => 'أدوات التثبيت', 'slug' => 'workholding'],
            'Cutting Tools' => ['ar' => 'أدوات القطع', 'slug' => 'cutting-tools'],
            'Lathe Tooling' => ['ar' => 'أدوات المخارط', 'slug' => 'lathe-tooling'],
            'Measuring' => ['ar' => 'أدوات القياس', 'slug' => 'measuring'],
        ];

        $categories = collect($categoryData)->mapWithKeys(function (array $data, string $englishName): array {
            $category = Category::query()->firstOrNew(['slug' => $data['slug']]);
            $category->fill([
                'is_active' => true,
                'en' => ['name' => $englishName, 'description' => "Professional {$englishName} supplies for CNC and manual machining."],
                'ar' => ['name' => $data['ar'], 'description' => 'مستلزمات احترافية لماكينات CNC والتشغيل اليدوي.'],
            ])->save();

            return [$englishName => $category];
        });

        $brands = collect(['Vertex', 'Sandvik', 'Mitutoyo'])->mapWithKeys(function (string $name): array {
            $brand = Brand::query()->firstOrNew(['slug' => str($name)->slug()]);
            $brand->fill([
                'is_active' => true,
                'en' => ['name' => $name, 'description' => "Trusted {$name} industrial tooling."],
                'ar' => ['name' => $name, 'description' => "أدوات صناعية موثوقة من {$name}."],
            ])->save();

            return [$name => $brand];
        });

        $products = [
            ['en' => 'Precision 3-Jaw Lathe Chuck', 'ar' => 'ظرف مخرطة دقيق ثلاثي الفكوك', 'sku' => 'WH-3J-200', 'category' => 'Workholding', 'brand' => 'Vertex'],
            ['en' => 'Modular Boring Bar Set', 'ar' => 'طقم قضبان تجويف معياري', 'sku' => 'LT-MB-12', 'category' => 'Lathe Tooling', 'brand' => 'Sandvik'],
            ['en' => 'Digital Outside Micrometer', 'ar' => 'ميكرومتر خارجي رقمي', 'sku' => 'MT-DM-025', 'category' => 'Measuring', 'brand' => 'Mitutoyo'],
        ];

        foreach ($products as $data) {
            $product = Product::query()->firstOrNew(['sku' => $data['sku']]);
            $product->fill([
                'slug' => str($data['en'])->slug(),
                'category_id' => $categories[$data['category']]->id,
                'brand_id' => $brands[$data['brand']]->id,
                'is_featured' => true,
                'is_active' => true,
                'en' => ['name' => $data['en'], 'summary' => "Production-ready {$data['en']} for demanding shop-floor applications.", 'description' => 'Selected for dependable performance, repeatable accuracy, and long service life in professional machining environments.', 'specifications' => ['Material' => 'Hardened steel', 'Application' => 'CNC and manual machining']],
                'ar' => ['name' => $data['ar'], 'summary' => 'أداة جاهزة للإنتاج ومناسبة لتطبيقات التشغيل الاحترافية.', 'description' => 'مختارة للأداء الموثوق والدقة المتكررة وعمر الخدمة الطويل في بيئات التشغيل الاحترافية.', 'specifications' => ['الخامة' => 'فولاذ مقسى', 'الاستخدام' => 'تشغيل CNC ويدوي']],
            ])->save();
        }
    }
}
