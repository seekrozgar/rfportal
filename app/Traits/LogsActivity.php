<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create');
        });

        static::updated(function ($model) {
            $model->logActivity('update');
        });

        static::deleted(function ($model) {
            $model->logActivity('delete');
        });
    }

    public function logActivity($action)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_type' => auth()->user()->role ?? 'system',
            'module' => class_basename($this),
            'action' => $action,
            'description' => $this->getActivityDescription($action),
            'old_data' => $action == 'update' ? $this->getOriginal() : null,
            'new_data' => $action != 'delete' ? $this->toArray() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function getActivityDescription($action)
    {
        $class = class_basename($this);
        $id = $this->id ?? 'new';
        $name = $this->name ?? $this->title ?? $id;

        $descriptions = [
            'create' => "Created new {$class}: {$name}",
            'update' => "Updated {$class}: {$name}",
            'delete' => "Deleted {$class}: {$name}",
        ];

        return $descriptions[$action] ?? "{$action} {$class}";
    }
}
