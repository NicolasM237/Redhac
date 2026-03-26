<?php

namespace App\Exports;

use App\Models\Violences;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ViolencesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $violences;

    public function __construct(Collection $violences)
    {
        $this->violences = $violences;
    }

    public function collection()
    {
        return $this->violences; // ✅ on utilise les données filtrées
    }

    public function headings(): array
    {
        return [
            'Code',
            'Status',
            'Contact',
            'Occupation',
            'Âge',
            'Sexe',
            'Nationalité',
            'Résidence',
            'Date survenue',
            'Lieu survenue',
            'Situation',
            'Auteurs',
            'Nature (Nom)',
            'Collecte (Nom)',
            'Description du cas',
            'Mesure OBC',
            'Risque Victime',
            'Attente Victime',
            'Fichier 1',
            'Fichier 2',
            'Fichier 3'
        ];
    }

    public function map($violence): array
    {
        return [
            $violence->code ?? 'N/A',
            $violence->status ?? 'N/A',
            $violence->contact ?? 'N/A',
            $violence->occupation ?? 'N/A',
            $violence->age ?? 'N/A',
            $violence->sexe ?? 'N/A',
            $violence->nationalite ?? 'N/A',
            $violence->residence ?? 'N/A',
            $this->formatDate($violence->datesurvenue),
            $violence->lieusurvenue ?? 'N/A',
            $violence->situation ?? 'N/A',
            $violence->auteurs ?? 'N/A',
            $violence->nature->nom ?? 'N/A',
            $violence->collecte->nom ?? 'N/A',
            $violence->description_cas ?? 'N/A',
            $violence->mesure_obc ?? 'N/A',
            $violence->risque_victime ?? 'N/A',
            $violence->attente_victime ?? 'N/A',
            $violence->fichier1 ? 'Présent' : 'Aucun',
            $violence->fichier2 ? 'Présent' : 'Aucun',
            $violence->fichier3 ? 'Présent' : 'Aucun',
        ];
    }

    private function formatDate($date)
    {
        if (!$date) return 'N/A';
        try {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        } catch (\Exception $e) {
            return $date;
        }
    }
}
