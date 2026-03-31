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
    protected $columns;

    // Mapping complet de tous les champs disponibles
    protected $fullColumns = [
        'code' => 'Code',
        'status' => 'Status',
        'contact' => 'Contact',
        'occupation' => 'Occupation',
        'age' => 'Âge',
        'sexe' => 'Sexe',
        'nationalite' => 'Nationalité',
        'residence' => 'Résidence',
        'datesurvenue' => 'Date survenue',
        'lieusurvenue' => 'Lieu survenue',
        'situation' => 'Situation',
        'auteurs' => 'Auteurs',
        'nature' => 'Nature (Nom)',
        'collecte' => 'Collecte (Nom)',
        'description_cas' => 'Description du cas',
        'mesure_obc' => 'Mesure OBC',
        'risque_victime' => 'Risque Victime',
        'attente_victime' => 'Attente Victime',
        'fichiers' => 'Fichiers'
    ];

    // Ordre des colonnes
    protected $columnOrder = [
        'code', 'status', 'contact', 'occupation', 'age', 'sexe', 
        'nationalite', 'residence', 'datesurvenue', 'lieusurvenue', 
        'situation', 'auteurs', 'nature', 'collecte', 'description_cas', 
        'mesure_obc', 'risque_victime', 'attente_victime', 'fichiers'
    ];

    public function __construct(Collection $violences, array $columns = [])
    {
        $this->violences = $violences;
        // Si aucune colonne spécifiée, exporter toutes
        $this->columns = !empty($columns) ? $columns : array_keys($this->fullColumns);
    }

    public function collection()
    {
        return $this->violences;
    }

    public function headings(): array
    {
        $headings = [];
        foreach ($this->columns as $column) {
            if (isset($this->fullColumns[$column])) {
                $headings[] = $this->fullColumns[$column];
            }
        }
        return $headings;
    }

    public function map($violence): array
    {
        $row = [];
        foreach ($this->columns as $column) {
            switch ($column) {
                case 'code':
                    $row[] = $violence->code ?? 'N/A';
                    break;
                case 'status':
                    $row[] = $violence->status ?? 'N/A';
                    break;
                case 'contact':
                    $row[] = $violence->contact ?? 'N/A';
                    break;
                case 'occupation':
                    $row[] = $violence->occupation ?? 'N/A';
                    break;
                case 'age':
                    $row[] = $violence->age ?? 'N/A';
                    break;
                case 'sexe':
                    $row[] = $violence->sexe ?? 'N/A';
                    break;
                case 'nationalite':
                    $row[] = $violence->nationalite ?? 'N/A';
                    break;
                case 'residence':
                    $row[] = $violence->residence ?? 'N/A';
                    break;
                case 'datesurvenue':
                    $row[] = $this->formatDate($violence->datesurvenue);
                    break;
                case 'lieusurvenue':
                    $row[] = $violence->lieusurvenue ?? 'N/A';
                    break;
                case 'situation':
                    $row[] = $violence->situation ?? 'N/A';
                    break;
                case 'auteurs':
                    $row[] = $violence->auteurs ?? 'N/A';
                    break;
                case 'nature':
                    $row[] = $violence->nature->nom ?? 'N/A';
                    break;
                case 'collecte':
                    $row[] = $violence->collecte->nom ?? 'N/A';
                    break;
                case 'description_cas':
                    $row[] = $violence->description_cas ?? 'N/A';
                    break;
                case 'mesure_obc':
                    $row[] = $violence->mesure_obc ?? 'N/A';
                    break;
                case 'risque_victime':
                    $row[] = $violence->risque_victime ?? 'N/A';
                    break;
                case 'attente_victime':
                    $row[] = $violence->attente_victime ?? 'N/A';
                    break;
                case 'fichiers':
                    $files = [];
                    if ($violence->fichier1) $files[] = 'Fichier 1';
                    if ($violence->fichier2) $files[] = 'Fichier 2';
                    if ($violence->fichier3) $files[] = 'Fichier 3';
                    $row[] = !empty($files) ? implode(', ', $files) : 'Aucun';
                    break;
            }
        }
        return $row;
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

