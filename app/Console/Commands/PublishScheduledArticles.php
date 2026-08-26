<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish approved articles whose scheduled published_at has been reached';

    public function handle(): int
    {
        $articles = Article::where('status', 'approved')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($articles as $article) {
            $article->update(['status' => 'published']);
            $count++;
        }

        $this->info("Published {$count} scheduled article(s).");

        return self::SUCCESS;
    }
}
