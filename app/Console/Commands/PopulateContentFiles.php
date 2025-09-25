<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContentFile;
use Illuminate\Support\Facades\File;

class PopulateContentFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:populate {--source=bonus.ca} {--location=ca_on} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate content_files table from markdown files in storage/app/public/files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting content files population...');
        
        $source = $this->option('source');
        $location = $this->option('location');
        $force = $this->option('force');
        
        $filesDirectory = storage_path('app/public/files');
        
        if (!is_dir($filesDirectory)) {
            $this->error("Directory not found: {$filesDirectory}");
            $this->info("Please create the directory and add your .md files there.");
            return 1;
        }

        // Find all .md files
        $markdownFiles = glob($filesDirectory . '/*.md');
        
        if (empty($markdownFiles)) {
            $this->error("No .md files found in {$filesDirectory}");
            $this->info("Please add your markdown files to the directory.");
            return 1;
        }

        $this->info("Found " . count($markdownFiles) . " markdown files to process...");
        $this->info("Source: {$source}, Location: {$location}");

        $processed = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($markdownFiles as $filePath) {
            $filename = basename($filePath);
            
            try {
                $content = file_get_contents($filePath);
                $fileHash = ContentFile::generateFileHash($content);
                
                // Check if file already exists (unless force is used)
                if (!$force && ContentFile::existsByHash($fileHash)) {
                    $this->line("⏭️  Skipping {$filename} (already exists)");
                    $skipped++;
                    continue;
                }
                
                // Create or update content file
                $contentFile = ContentFile::updateOrCreate(
                    ['file_hash' => $fileHash],
                    [
                        'filename' => $filename,
                        'content' => $content,
                        'file_path' => $filePath,
                        'metadata' => $this->generateMetadataForFile($filename, $content),
                        'status' => ContentFile::STATUS_PENDING,
                        'source_site' => $source,
                        'file_hash' => $fileHash,
                        'location' => $location,
                        'processed_at' => null,
                        'error_message' => null
                    ]
                );
                
                $this->line("✅ Processed {$filename} (ID: {$contentFile->id})");
                $processed++;
                
            } catch (\Exception $e) {
                $this->error("❌ Failed to process {$filename}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n📊 Summary:");
        $this->info("✅ Processed: {$processed}");
        $this->info("⏭️  Skipped: {$skipped}");
        $this->info("❌ Errors: {$errors}");
        
        return 0;
    }
    
    private function generateMetadataForFile(string $filename, string $content): array
    {
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        
        // Extract category from filename
        $category = $this->extractCategoryFromFilename($nameWithoutExt);
        
        // Generate title from filename
        $title = $this->generateTitleFromFilename($nameWithoutExt);
        
        return [
            'title' => $title,//passed by api
            'category' => $category,//do we need this?todo:
            'file_size' => strlen($content),
            'word_count' => str_word_count($content),
            'line_count' => substr_count($content, "\n") + 1,
            'created_at' => now()->toISOString(),
            'type' => 'markdown',
            'oplist_url'=> 'https://bonusca.gdcgroup.io/private/country-ca/region-ab/rank-1/language-en',
            'oplist_type'=>'oplist',//brand,oplist//
            'author'=>'Henri Hardcoded',
            'fact_checker'=>'',
            'site_url'=>'https://bonus.ca',
            'location'=>'en_CA',
            'updated_at'=>now()->toISOString(),
        ];
    }
    
    private function extractCategoryFromFilename(string $filename): string
    {
        $categoryMap = [
            'online-casinos' => 'casino-guide',
            'casino-review' => 'casino-review',
            'bonus' => 'bonus-guide',
            'guide' => 'guide',
            'review' => 'review',
            'news' => 'news',
            'blog' => 'blog'
        ];
        
        foreach ($categoryMap as $pattern => $category) {
            if (str_contains($filename, $pattern)) {
                return $category;
            }
        }
        
        return 'documentation';
    }
    
    private function generateTitleFromFilename(string $filename): string
    {
        $title = str_replace('-', ' ', $filename);
        $title = ucwords($title);
        
        return $title;
    }
}
