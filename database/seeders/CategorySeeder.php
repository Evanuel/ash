<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() > 0) {
            return;
        }

        $categories = [
            [
                'name' => 'Tecnologia',
                'slug' => 'tecnologia',
                'description' => 'Empresas de tecnologia, software e hardware',
                'type' => 'company_category',
                'order' => 1,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Comércio',
                'slug' => 'comercio',
                'description' => 'Lojas, varejo e atacado',
                'type' => 'company_category',
                'order' => 2,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Serviços',
                'slug' => 'servicos',
                'description' => 'Prestadores de serviços diversos',
                'type' => 'company_category',
                'order' => 3,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Indústria',
                'slug' => 'industria',
                'description' => 'Indústrias e fábricas',
                'type' => 'company_category',
                'order' => 4,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Construção Civil',
                'slug' => 'construcao-civil',
                'description' => 'Construtoras e empresas do setor imobiliário',
                'type' => 'company_category',
                'order' => 5,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Saúde',
                'slug' => 'saude',
                'description' => 'Clínicas, hospitais e farmácias',
                'type' => 'company_category',
                'order' => 6,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Educação',
                'slug' => 'educacao',
                'description' => 'Escolas, faculdades e cursos',
                'type' => 'company_category',
                'order' => 7,
                'active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Alimentos e Bebidas',
                'slug' => 'alimentos-bebidas',
                'description' => 'Restaurantes, bares e indústrias alimentícias',
                'type' => 'company_category',
                'order' => 8,
                'active' => true,
                'created_by' => 1,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            
            // Criar algumas subcategorias para cada categoria
            $this->createSubcategories($category);
        }

        $this->command->info('Categories and subcategories seeded successfully.');
    }

    /**
     * Criar subcategorias para uma categoria
     */
    private function createSubcategories(Category $category): void
    {
        $subcategories = [];
        
        switch ($category->slug) {
            case 'tecnologia':
                $subcategories = [
                    ['name' => 'Desenvolvimento de Software', 'slug' => 'desenvolvimento-software'],
                    ['name' => 'Hardware e Equipamentos', 'slug' => 'hardware-equipamentos'],
                    ['name' => 'Consultoria em TI', 'slug' => 'consultoria-ti'],
                    ['name' => 'Infraestrutura de Redes', 'slug' => 'infraestrutura-redes'],
                    ['name' => 'Segurança da Informação', 'slug' => 'seguranca-informacao'],
                ];
                break;
                
            case 'comercio':
                $subcategories = [
                    ['name' => 'Varejo', 'slug' => 'varejo'],
                    ['name' => 'Atacado', 'slug' => 'atacado'],
                    ['name' => 'E-commerce', 'slug' => 'ecommerce'],
                    ['name' => 'Importação', 'slug' => 'importacao'],
                    ['name' => 'Exportação', 'slug' => 'exportacao'],
                ];
                break;
                
            case 'servicos':
                $subcategories = [
                    ['name' => 'Consultoria', 'slug' => 'consultoria'],
                    ['name' => 'Limpeza', 'slug' => 'limpeza'],
                    ['name' => 'Manutenção', 'slug' => 'manutencao'],
                    ['name' => 'Transporte', 'slug' => 'transporte'],
                    ['name' => 'Marketing Digital', 'slug' => 'marketing-digital'],
                ];
                break;
        }

        foreach ($subcategories as $index => $subcatData) {
            Category::create([
                'client_id' => 0,
                'parent_id' => $category->id,
                'name' => $subcatData['name'],
                'slug' => $subcatData['slug'],
                'description' => "Subcategoria de {$category->name}",
                'type' => 'company_subcategory',
                'order' => $index + 1,
                'active' => true,
                'created_by' => 1,
            ]);
        }
    }
}