<?php

namespace Database\Seeders;

use App\Models\Build;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Component;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Роли
        $userRole = Role::firstOrCreate(['name' => 'Пользователь']);
        $builderRole = Role::firstOrCreate(['name' => 'Сборщик']);
        $adminRole = Role::firstOrCreate(['name' => 'Администратор']);

        // 2. Профили: user, builder, admin (пароль password123)
        $user = User::firstOrCreate(
            ['login' => 'user'],
            [
                'nickname' => 'Алексей',
                'bio' => 'Выбираю комплектующие для домашнего ПК.',
                'role_id' => $userRole->id,
                'password' => 'password123',
            ]
        );

        $builder = User::firstOrCreate(
            ['login' => 'builder'],
            [
                'nickname' => 'Илья (Сборщик)',
                'bio' => 'Собираю рабочие и игровые конфигурации под разный бюджет.',
                'role_id' => $builderRole->id,
                'password' => 'password123',
            ]
        );

        $admin = User::firstOrCreate(
            ['login' => 'admin'],
            [
                'nickname' => 'Администратор',
                'bio' => 'Модерация сборок и обновление базы комплектующих.',
                'role_id' => $adminRole->id,
                'password' => 'password123',
            ]
        );

        // 3. Категории
        $catGaming = Category::firstOrCreate(['slug' => 'gaming'], ['name' => 'Игровые']);
        $catWork = Category::firstOrCreate(['slug' => 'workstation'], ['name' => 'Для работы и монтажа']);
        $catOffice = Category::firstOrCreate(['slug' => 'office'], ['name' => 'Офисные / Бюджетные']);

        // 4. Комплектующие: минимум по 8 позиций в каждой категории и 6 корпусов

        // --- Процессоры (CPU) - 8 шт. ---
        $cpu12100 = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'Intel Core i3-12100F'],
            ['socket' => 'LGA1700', 'ram_type' => null, 'power_w' => 65, 'tier' => 1, 'price' => 8200]
        );
        $cpu5600 = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'AMD Ryzen 5 5600'],
            ['socket' => 'AM4', 'ram_type' => null, 'power_w' => 65, 'tier' => 2, 'price' => 10900]
        );
        $cpu12400 = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'Intel Core i5-12400F'],
            ['socket' => 'LGA1700', 'ram_type' => null, 'power_w' => 80, 'tier' => 2, 'price' => 12400]
        );
        $cpu7500f = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'AMD Ryzen 5 7500F'],
            ['socket' => 'AM5', 'ram_type' => null, 'power_w' => 88, 'tier' => 3, 'price' => 15800]
        );
        $cpu13600k = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'Intel Core i5-13600KF'],
            ['socket' => 'LGA1700', 'ram_type' => null, 'power_w' => 150, 'tier' => 4, 'price' => 25900]
        );
        $cpu7700 = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'AMD Ryzen 7 7700'],
            ['socket' => 'AM5', 'ram_type' => null, 'power_w' => 105, 'tier' => 4, 'price' => 26500]
        );
        $cpu14700k = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'Intel Core i7-14700K'],
            ['socket' => 'LGA1700', 'ram_type' => null, 'power_w' => 190, 'tier' => 4, 'price' => 38500]
        );
        $cpu7800x3d = Component::firstOrCreate(
            ['type' => 'cpu', 'name' => 'AMD Ryzen 7 7800X3D'],
            ['socket' => 'AM5', 'ram_type' => null, 'power_w' => 120, 'tier' => 5, 'price' => 42900]
        );

        // --- Материнские платы (Motherboard) - 8 шт. ---
        $mbH610 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'GIGABYTE H610M H V2 DDR4'],
            ['socket' => 'LGA1700', 'ram_type' => 'DDR4', 'power_w' => 25, 'tier' => 1, 'price' => 7400]
        );
        $mbB550 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'MSI B550M PRO-VDH WIFI'],
            ['socket' => 'AM4', 'ram_type' => 'DDR4', 'power_w' => 30, 'tier' => 2, 'price' => 10200]
        );
        $mbB760D4 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'MSI PRO B760M-P DDR4'],
            ['socket' => 'LGA1700', 'ram_type' => 'DDR4', 'power_w' => 30, 'tier' => 2, 'price' => 10500]
        );
        $mbA620 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'ASRock A620M Pro RS'],
            ['socket' => 'AM5', 'ram_type' => 'DDR5', 'power_w' => 30, 'tier' => 2, 'price' => 11900]
        );
        $mbB760D5 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'ASUS PRIME B760-PLUS'],
            ['socket' => 'LGA1700', 'ram_type' => 'DDR5', 'power_w' => 35, 'tier' => 3, 'price' => 14900]
        );
        $mbB650M = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'GIGABYTE B650M DS3H'],
            ['socket' => 'AM5', 'ram_type' => 'DDR5', 'power_w' => 35, 'tier' => 3, 'price' => 15600]
        );
        $mbB650Toma = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'MSI MAG B650 TOMAHAWK WIFI'],
            ['socket' => 'AM5', 'ram_type' => 'DDR5', 'power_w' => 40, 'tier' => 4, 'price' => 21900]
        );
        $mbZ790 = Component::firstOrCreate(
            ['type' => 'motherboard', 'name' => 'ASUS TUF GAMING Z790-PLUS WIFI'],
            ['socket' => 'LGA1700', 'ram_type' => 'DDR5', 'power_w' => 45, 'tier' => 5, 'price' => 24900]
        );

        // --- Видеокарты (GPU) - 8 шт. ---
        $gpu1650 = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GTX 1650 4GB GDDR6'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 75, 'tier' => 1, 'price' => 14500]
        );
        $gpu6600 = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'AMD Radeon RX 6600 8GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 132, 'tier' => 2, 'price' => 22900]
        );
        $gpu3060 = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GeForce RTX 3060 12GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 170, 'tier' => 2, 'price' => 29500]
        );
        $gpu4060 = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GeForce RTX 4060 8GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 115, 'tier' => 3, 'price' => 33900]
        );
        $gpu7700xt = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'AMD Radeon RX 7700 XT 12GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 245, 'tier' => 3, 'price' => 47900]
        );
        $gpu4070s = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GeForce RTX 4070 Super 12GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 220, 'tier' => 4, 'price' => 68900]
        );
        $gpu4080s = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GeForce RTX 4080 Super 16GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 320, 'tier' => 5, 'price' => 114900]
        );
        $gpu5090 = Component::firstOrCreate(
            ['type' => 'gpu', 'name' => 'GeForce RTX 5090 32GB'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 575, 'tier' => 5, 'price' => 249900]
        );

        // --- Оперативная память (RAM) - 7 шт. ---
        $ram8d4 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'Crucial 8GB DDR4-3200'],
            ['socket' => null, 'ram_type' => 'DDR4', 'power_w' => 5, 'tier' => 1, 'price' => 2100]
        );
        $ram16d4 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'Kingston FURY Beast 16GB (2x8GB) DDR4-3200'],
            ['socket' => null, 'ram_type' => 'DDR4', 'power_w' => 10, 'tier' => 2, 'price' => 4400]
        );
        $ram32d4 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'G.Skill Ripjaws V 32GB (2x16GB) DDR4-3600'],
            ['socket' => null, 'ram_type' => 'DDR4', 'power_w' => 12, 'tier' => 3, 'price' => 7600]
        );
        $ram16d5 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'Kingston FURY Beast 16GB (2x8GB) DDR5-5200'],
            ['socket' => null, 'ram_type' => 'DDR5', 'power_w' => 10, 'tier' => 2, 'price' => 6900]
        );
        $ram32d5_5600 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'ADATA XPG Lancer 32GB (2x16GB) DDR5-5600'],
            ['socket' => null, 'ram_type' => 'DDR5', 'power_w' => 14, 'tier' => 3, 'price' => 10900]
        );
        $ram32d5_6000 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'G.Skill Trident Z5 32GB (2x16GB) DDR5-6000'],
            ['socket' => null, 'ram_type' => 'DDR5', 'power_w' => 15, 'tier' => 4, 'price' => 12900]
        );
        $ram64d5 = Component::firstOrCreate(
            ['type' => 'ram', 'name' => 'Corsair Vengeance 64GB (2x32GB) DDR5-6000'],
            ['socket' => null, 'ram_type' => 'DDR5', 'power_w' => 20, 'tier' => 5, 'price' => 23500]
        );

        // --- Блоки питания (PSU) - 7 шт. ---
        $psu450 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'AeroCool VX Plus 450W'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 450, 'tier' => 1, 'price' => 2900]
        );
        $psu500 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'DeepCool PF500 500W'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 500, 'tier' => 2, 'price' => 4200]
        );
        $psu650 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'DeepCool PK650D 650W (80+ Bronze)'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 650, 'tier' => 3, 'price' => 5900]
        );
        $psu750 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'be quiet! Pure Power 12 M 750W (80+ Gold)'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 750, 'tier' => 4, 'price' => 11900]
        );
        $psu850 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'MSI MAG A850GL PCIE5 850W (80+ Gold)'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 850, 'tier' => 4, 'price' => 13500]
        );
        $psu1000 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'Seasonic Vertex GX-1000 1000W (80+ Gold)'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 1000, 'tier' => 5, 'price' => 21900]
        );
        $psu1200 = Component::firstOrCreate(
            ['type' => 'psu', 'name' => 'be quiet! Dark Power 13 1200W (80+ Titanium)'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 1200, 'tier' => 5, 'price' => 31500]
        );

        // --- Корпуса (Case) - 6 шт. ---
        $caseMini = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'DeepCool Matrexx 30'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 1, 'price' => 3400]
        );
        $caseAir = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'ARDOR GAMING Rare Minicase MS2'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 2, 'price' => 4600]
        );
        $case55 = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'DeepCool CC560 V2'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 3, 'price' => 5900]
        );
        $caseLancool = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'Lian Li LANCOOL 216'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 4, 'price' => 9800]
        );
        $caseNzxt = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'NZXT H5 Flow'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 4, 'price' => 10500]
        );
        $caseFractal = Component::firstOrCreate(
            ['type' => 'case', 'name' => 'Fractal Design North'],
            ['socket' => null, 'ram_type' => null, 'power_w' => 0, 'tier' => 5, 'price' => 16900]
        );

        if (Build::count() > 0) {
            return;
        }

        // 5. Стартовые сборки от builder во всех категориях со спокойными описаниями

        // Сборка 1: Игровые (Зелёный индикатор)
        $eval1 = Build::evaluateCompatibility($cpu7500f, $mbB650M, $gpu4070s, $ram32d5_6000, $psu750, $caseLancool);
        $build1 = Build::create([
            'title' => 'Сборка для игр в 2K на Ryzen 5 7500F и RTX 4070 Super',
            'description' => 'Оптимальный вариант под монитор 1440p. Плата на чипсете B650 с запасом под будущий апгрейд процессора, продуваемый корпус и тихий блок питания на 750 Вт.',
            'user_id' => $builder->id,
            'category_id' => $catGaming->id,
            'total_price' => $eval1['total_price'],
            'compatibility_status' => $eval1['status'],
            'compatibility_message' => $eval1['message'],
        ]);
        $build1->components()->sync([$cpu7500f->id, $mbB650M->id, $gpu4070s->id, $ram32d5_6000->id, $psu750->id, $caseLancool->id]);
        Comment::create([
            'build_id' => $build1->id,
            'user_id' => $user->id,
            'body' => 'Хороший подбор комплектующих. Обычного башенного кулера на 4 трубки хватит для 7500F?',
        ]);

        // Сборка 2: Игровые (Жёлтый индикатор: слабый Core i3 + мощная RTX 5090)
        $eval2 = Build::evaluateCompatibility($cpu12100, $mbB760D4, $gpu5090, $ram16d4, $psu1000, $case55);
        $build2 = Build::create([
            'title' => 'Пример несбалансированной сборки: Core i3-12100F и RTX 5090',
            'description' => 'Тестовая конфигурация для демонстрации проверки баланса. Разъёмы и блок питания подходят, но бюджетный четырёхъядерный процессор не способен загрузить RTX 5090.',
            'user_id' => $builder->id,
            'category_id' => $catGaming->id,
            'total_price' => $eval2['total_price'],
            'compatibility_status' => $eval2['status'],
            'compatibility_message' => $eval2['message'],
        ]);
        $build2->components()->sync([$cpu12100->id, $mbB760D4->id, $gpu5090->id, $ram16d4->id, $psu1000->id, $case55->id]);
        Comment::create([
            'build_id' => $build2->id,
            'user_id' => $user->id,
            'body' => 'Наглядно видно, почему сюда нужен процессор уровня Ryzen 7 7800X3D или Core i7.',
        ]);

        // Сборка 3: Для работы и монтажа (Зелёный индикатор)
        $eval3 = Build::evaluateCompatibility($cpu14700k, $mbZ790, $gpu4070s, $ram64d5, $psu850, $caseFractal);
        $build3 = Build::create([
            'title' => 'Рабочий ПК для монтажа и 3D (Core i7-14700K, 64 ГБ ОЗУ)',
            'description' => 'Конфигурация для работы с графикой, рендеринга и компиляции больших проектов. Установлено 64 ГБ памяти DDR5 и блок питания на 850 Вт.',
            'user_id' => $builder->id,
            'category_id' => $catWork->id,
            'total_price' => $eval3['total_price'],
            'compatibility_status' => $eval3['status'],
            'compatibility_message' => $eval3['message'],
        ]);
        $build3->components()->sync([$cpu14700k->id, $mbZ790->id, $gpu4070s->id, $ram64d5->id, $psu850->id, $caseFractal->id]);
        Comment::create([
            'build_id' => $build3->id,
            'user_id' => $user->id,
            'body' => 'Для работы в Premiere и Blender объём оперативной памяти 64 ГБ как раз кстати.',
        ]);

        // Сборка 4: Офисные / Бюджетные (Зелёный индикатор)
        $eval4 = Build::evaluateCompatibility($cpu12100, $mbB760D4, $gpu6600, $ram16d4, $psu500, $caseAir);
        $build4 = Build::create([
            'title' => 'Недорогой ПК для учёбы и игр в Full HD',
            'description' => 'Базовая сборка на сокете LGA1700 с 16 ГБ оперативной памяти DDR4 и видеокартой RX 6600. Подойдёт для учебных программ и нетребовательных игр.',
            'user_id' => $builder->id,
            'category_id' => $catOffice->id,
            'total_price' => $eval4['total_price'],
            'compatibility_status' => $eval4['status'],
            'compatibility_message' => $eval4['message'],
        ]);
        $build4->components()->sync([$cpu12100->id, $mbB760D4->id, $gpu6600->id, $ram16d4->id, $psu500->id, $caseAir->id]);
        Comment::create([
            'build_id' => $build4->id,
            'user_id' => $user->id,
            'body' => 'Недорогая и практичная сборка, ничего лишнего.',
        ]);
    }
}
