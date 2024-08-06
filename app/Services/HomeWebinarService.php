<?php

namespace App\Services;

use App\Models\Webinar;

class HomeWebinarService
{
    public function getAvailableWebinars()
    {
        return Webinar::whereHas('events', function ($q) {
            $q->where('date', '>=', getCurrentDate())
                ->where('active', 'S');
        })
            ->with([
                'events' => fn($q2) =>
                    $q2->where('date', '>=', getCurrentDate())
                        ->where('active', 'S')
                        ->with(['instructor', 'room'])
                        ->withCount(['certifications']),
                'file' => fn($q3) =>
                    $q3->where('file_type', 'imagenes')
                        ->where('category', 'cursos')
            ])
            ->get();
    }

    public function getAvailableEvents(Webinar $webinar)
    {
        return $webinar->events()
            ->where('date', '>=', getCurrentDate())
            ->where('active', 'S')
            ->with([
                'instructor',
                'room',
                'certifications',
                'file' => fn ($q) =>
                $q->where('file_type', 'imagenes')
            ])
            ->get();
    }

}

