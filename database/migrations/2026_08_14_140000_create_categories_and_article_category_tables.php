<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_id')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['article_id', 'category_id']);
        });

        // Migrate existing category string data from articles table to categories & pivot
        if (Schema::hasColumn('articles', 'category')) {
            $articles = DB::table('articles')->whereNotNull('category')->where('category', '!=', '')->get();
            $now = now();

            foreach ($articles as $article) {
                $categoryName = trim($article->category);
                if (empty($categoryName)) {
                    continue;
                }

                $slug = Str::slug($categoryName);

                $category = DB::table('categories')->where('slug', $slug)->first();
                if (! $category) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'name' => $categoryName,
                        'name_id' => $categoryName,
                        'slug' => $slug,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $categoryId = $category->id;
                }

                // Attach to pivot table if not already attached
                $exists = DB::table('article_category')
                    ->where('article_id', $article->id)
                    ->where('category_id', $categoryId)
                    ->exists();

                if (! $exists) {
                    DB::table('article_category')->insert([
                        'article_id' => $article->id,
                        'category_id' => $categoryId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');
    }
};
