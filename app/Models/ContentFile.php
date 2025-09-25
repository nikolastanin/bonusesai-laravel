<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ContentFile extends Model
{
    protected $table = 'content_files';
    
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'filename',
        'content',
        'file_path',
        'metadata',
        'status',
        'source_site',
        'file_hash',
        'processed_at',
        'error_message',
        'location'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    
    // Source site constants
    const SOURCE_BONUS_CA = 'bonus.ca';
    const SOURCE_BONUSFINDER = 'bonusfinder.com';
    
    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }
    
    public function scopeBySource($query, string $source)
    {
        return $query->where('source_site', $source);
    }
    
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }
    
    public function scopeByFilename($query, string $filename)
    {
        return $query->where('filename', $filename);
    }
    
    // Helper methods
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'processed_at' => now()
        ]);
    }
    
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now(),
            'error_message' => null
        ]);
    }
    
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
            'processed_at' => now()
        ]);
    }
    
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }
    
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
    
    // Generate file hash for deduplication
    public static function generateFileHash(string $content): string
    {
        return hash('sha256', $content);
    }
    
    // Check if file already exists (by hash)
    public static function existsByHash(string $hash): bool
    {
        return static::where('file_hash', $hash)->exists();
    }
    
    // Find by hash
    public static function findByHash(string $hash): ?static
    {
        return static::where('file_hash', $hash)->first();
    }
}
