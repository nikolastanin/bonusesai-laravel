# Content Files System

## Overview
This project now uses a `content_files` table to store scraped markdown content instead of vector embeddings.

## Database Schema
- `content_files` table stores markdown content with metadata
- UUID primary keys for better scalability
- File deduplication via SHA256 hashing
- Status tracking (pending, processing, completed, failed)
- Location and source site tracking

## Commands
- `php artisan content:populate` - Populate content_files from markdown files
- `php artisan content:populate --source=bonusfinder.com --location=us_nj` - With custom parameters
- `php artisan content:populate --force` - Force re-process existing files

## Models
- `ContentFile` - Main model for content management
- Helper methods for status management
- Scopes for filtering by status, source, location
- Deduplication support

## Features
- Automatic metadata generation
- File hash-based deduplication
- Rich status tracking
- Configurable source and location
- Performance indexes on key fields