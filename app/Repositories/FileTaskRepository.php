<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $folder = 'tasks';

    private function ensureFolderExists(): void
    {
        if (!Storage::exists($this->folder)) {
            Storage::makeDirectory($this->folder);
        }
    }

    public function all(): array
    {
        $this->ensureFolderExists();
        $tasks = [];
        foreach (Storage::files($this->folder) as $file) {
            $tasks[] = json_decode(Storage::get($file), true);
        }
        return $tasks;
    }

    public function create(array $data): array
    {
        $this->ensureFolderExists();
        $id = time();
        $data['id'] = $id;
        Storage::put("{$this->folder}/{$id}.json", json_encode($data, JSON_PRETTY_PRINT));
        return $data;
    }

    public function find(int $id): ?array
    {
        $this->ensureFolderExists();
        $path = "{$this->folder}/{$id}.json";
        if (!Storage::exists($path)) {
            return null;
        }
        return json_decode(Storage::get($path), true);
    }

    public function update(int $id, array $data): ?array
    {
        $this->ensureFolderExists();
        $path = "{$this->folder}/{$id}.json";
        if (!Storage::exists($path)) {
            return null;
        }
        $existing = json_decode(Storage::get($path), true);
        $updated = [
            'id' => $id,
            'title' => $data['title'] ?? ($existing['title'] ?? null),
            'completed' => $data['completed'] ?? ($existing['completed'] ?? false),
        ];
        Storage::put($path, json_encode($updated, JSON_PRETTY_PRINT));
        return $updated;
    }

    public function delete(int $id): bool
    {
        $this->ensureFolderExists();
        $path = "{$this->folder}/{$id}.json";
        if (!Storage::exists($path)) {
            return false;
        }
        return Storage::delete($path);
    }
}